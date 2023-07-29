<?php

namespace Drupal\block_practice\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Block Practice routes.
 */
class BlockPracticeController extends ControllerBase {

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
