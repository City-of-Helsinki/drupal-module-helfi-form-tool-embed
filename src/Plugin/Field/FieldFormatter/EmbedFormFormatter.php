<?php

namespace Drupal\helfi_formtool_embed\Plugin\Field\FieldFormatter;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'EmbedForm' formatter.
 *
 * @FieldFormatter(
 *   id = "embed_form",
 *   label = @Translation("EmbedForm"),
 *   field_types = {
 *     "embed_form"
 *   }
 * )
 */
class EmbedFormFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings(): array {
    return [
        'form_id' => NULL,
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $elements = [];

    // Settings from type.
    $settings = $this->getSettings();
    // field_settings on concrete field.
    $field_settings = $this->getFieldSettings();

    /** @var \Drupal\helfi_helsinki_profiili\HelsinkiProfiiliUserData $userDataService */
    $userDataService = \Drupal::service('helfi_helsinki_profiili.userdata');

    $userData = $userDataService->getUserData();

    foreach ($items as $delta => $item) {

      if ($userData === NULL) {
        $elements[$delta] = [
          '#theme' => 'embed_form',
          '#src' => 'NOAUTH',
          '#attributes' => [],
        ];
        continue;
      }

      $formToolUrl = getenv('FORM_TOOL_HOST');
      $embedDomainPath = $formToolUrl . '/fi/formtool/';

      $formMetadata = [];

      if (empty($item->embed_form_id)) {
        continue;
      }
      if (empty($item->embed_form_data)) {
        continue;
      }
      else {
        $formMetadata = Json::decode($item->embed_form_data);
      }

      $itemName = $item->getFieldDefinition()->getName();
      $itemParentId = $item->getParent()->getParent()->getEntity()->ID();
      $htmlid = 'iframe-' . $itemName . '-' . $itemParentId;

      $htmlid = preg_replace('#[^A-Za-z0-9\-\_]+#', '-', $htmlid);
      $options['id'] = $options['name'] = $htmlid;

      // Append active class.
      $options['class'] = 'form-tool-share-iframe ' . $htmlid;

      $options_link = [];
      $options_link['attributes'] = [];
      $options_link['attributes']['title'] = $options['title'] ?? '';
      $path = $embedDomainPath . $item->embed_form_id . '/share/iframe-resizer/4.2.10';

      $srcuri = Url::fromUri($path, $options_link);
      $src = $srcuri->toString();
      $options['src'] = $src;

      // Collect styles, but leave it overwritable.
      $style = '';
      $style .= 'width:1px;min-width:100%;';
      $style .= 'height:1000px;';
      $style .= '/*frameborder*/ border-width:0;';
      $style .= '/*transparency*/ background-color:transparent;';

      // Policy attribute.
      $allow[] = 'accelerometer';
      $allow[] = 'autoplay';
      $allow[] = 'camera';
      $allow[] = 'encrypted-media';
      $allow[] = 'geolocation';
      $allow[] = 'gyroscope';
      $allow[] = 'microphone';
      $allow[] = 'payment';
      $allow[] = 'picture-in-picture';
      $options['allow'] = implode(';', $allow);

      $options['allowfullscreen'] = TRUE;

      $drupal_attributes = new Attribute($options);

      $elements[$delta] = [
        '#theme' => 'embed_form',
        '#src' => $src,
        '#attributes' => $drupal_attributes,
        '#style' => 'iframe#' . $htmlid . ' {' . $style . '}',
        '#headerlevel' => 4,
      ];
    }

    return $elements;
  }

}
