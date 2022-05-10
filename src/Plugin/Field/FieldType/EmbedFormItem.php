<?php

namespace Drupal\helfi_formtool_embed\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'embed_form' field type.
 *
 * @FieldType(
 *   id = "embed_form",
 *   label = @Translation("EmbedForm Field"),
 *   category = @Translation("Helfi"),
 *   default_widget = "embed_form",
 *   default_formatter = "embed_form"
 * )
 *
 * @DCG
 * If you are implementing a single value field type you may want to inherit
 * this class form some of the field type classes provided by Drupal core.
 * Check out /core/lib/Drupal/Core/Field/Plugin/Field/FieldType directory for a
 * list of available field type implementations.
 */
class EmbedFormItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings(): array {
    return [
      'embed_form_id' => NULL,
      'embed_form_data' => NULL,
    ] + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    $value = $this->get('embed_form_id')->getValue();
    return $value === NULL || $value === '';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {

    // @DCG
    // See /core/lib/Drupal/Core/TypedData/Plugin/DataType directory for
    // available data types.
    $properties['embed_form_id'] = DataDefinition::create('string')
      ->setLabel(t('Form ID'))
      ->setRequired(TRUE);
    $properties['embed_form_data'] = DataDefinition::create('string')
      ->setLabel(t('Form Data'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {

    $columns = [
      'embed_form_id' => [
        'type' => 'varchar',
        'not null' => FALSE,
        'description' => 'ID of the embedded webform.',
        'length' => 50,
      ],
      'embed_form_data' => [
        'type' => 'text',
        'size' => 'normal',
        'not null' => FALSE,
        'description' => 'Metadata of the embed form.',
      ],
    ];

    $schema = [
      'columns' => $columns,
      // @DCG Add indexes here if necessary.
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName(): ?string {
    return 'embed_form_id';
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition): array {
    $random = new Random();
    $values['embed_form_id'] = $random->word(mt_rand(1, 50));
    return $values;
  }

}
