<?php

namespace Drupal\my_ajax\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for my_ajax routes.
 */
class MyAjaxController extends ControllerBase {

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
