<?php

namespace Drupal\helfi_formtool_embed\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for helfi_formtool_embed routes.
 */
class HelfiFormtoolEmbedController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
