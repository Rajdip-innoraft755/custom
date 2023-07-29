<?php

namespace Drupal\custom_practice\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Returns responses for custom_practice routes.
 */
class CustomPracticeController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build(string $value) {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works! An the page number is ' . $value),
    ];

    return $build;
  }

  /**
   * accessCheck method is to check whether the user has such permission to
   * access the related route.Its basically custom access checking for each
   * user.
   *
   *   @param AccountInterface $account
   *     Takes the user account as the only argument.
   *
   *   @return AccessResult
   *     Returns the AccessResult.
   */
  public function accessCheck(AccountInterface $account) {
    return AccessResult::allowedIf($account->hasPermission('access the custom page'));
  }

}
