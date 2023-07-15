<?php

namespace Drupal\block_practice\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides Welcome block.It shows the current user role.
 *
 * @Block(
 *   id = "block_practice_welcome",
 *   admin_label = @Translation("Welcome User"),
 *   category = @Translation("Block Practice")
 * )
 */
class WelcomeUserBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Stores the instance of AccountProxyInterface for Current User.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * Stores the object of CurrentRouteMatch.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected CurrentRouteMatch $route;

  /**
   * Constructs a WelcomeUserBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_defination
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   Stores the current user object.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $route
   *   Stores the object of CurrentRouteMatch.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_defination, AccountProxyInterface $current_user, CurrentRouteMatch $route) {
    parent::__construct($configuration, $plugin_id, $plugin_defination);
    $this->currentUser = $current_user;
    $this->route = $route;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('current_route_match'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function build() {
    $user = $this->currentUser;
    $role = implode(array_diff($user->getRoles(), ['authenticated']));
    $build['content'] = [
      '#markup' => $this->t('Welcome @role user', ['@role' => $role]),
    ];
    return $build;
  }

  /**
   * {@inheritDoc}
   */
  public function blockAccess(AccountInterface $account) {
    $route = $this->route->getRouteName();
    if ($route != 'block_practice.welcome') {
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
  }

}
