<?php

namespace Drupal\block_practice\Plugin\Block;

use Drupal;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\User;

/**
 * Provides Welcome block.It shows the current user role.
 *
 * @Block(
 *   id = "block_practice_welcome",
 *   admin_label = @Translation("Welcome User"),
 *   category = @Translation("Block Practice")
 * )
 */
class WelcomeUserBlock extends BlockBase implements BlockPluginInterface{

  /**
   * {@inheritDoc}
   */
  public function build() {
    $user = User::load(Drupal::currentUser()->id());
      $role = implode(array_diff($user->getRoles(), ['authenticated']));
      $build['content'] = [
        '#markup' => t('Welcome @role user' , ['@role' => $role]),
      ];
      return $build;
    }

  /**
   * {@inheritDoc}
   */
  public function blockAccess(AccountInterface $account) {
    $route = Drupal::routeMatch()->getRouteName();
    if ( $route != 'block_practice.welcome') {
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
  }

}
