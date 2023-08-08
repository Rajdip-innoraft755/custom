<?php

namespace Drupal\mymodule\Controller;

use Drupal;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheTagsInvalidator;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * FirstController is to sends the response to the perticular route
 */
class FirstController extends ControllerBase {

  /**
   * @var AccountInterface
   *   This variable to store the instance of current user.
   */
  protected AccountInterface $current_user;

  /**
   * Constructor is used for creating the instance of the FirstController Class
   * with the required dependency.
   *
   *   @param AccountProxyInterface $account
   *     Accepts the instance of AccountProxyInterface as the only dependency.
   */
  public function __construct(AccountProxyInterface $account, EntityTypeManager $entityTypeManager) {
    // $this->currentUser = $user->getStorage('user')->load($account->id());
    $this->current_user = $account;

  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static (
      $container->get('current_user'),
      $container->get('entity_type.manager'),
    );
  }


  /**
   * @method sayHello
   *  This function is used to show the result in /hello page.
   *
   *   @return array
   */
  public function sayHello() {
    return [
      '#type' => 'markup',
      '#markup' => t('hello world'),
    ];
  }

  /**
   * @method dynamicWelcome
   *  This method is written to practice fetching the dynamic value from URL
   *  and used those values.
   *
   *   @param string $param1
   *     This is to accept the first parameter.
   *
   *   @param string $param2
   *     This is to accept the second parameter.
   *
   *   @return array
   */
  public function dynamicWelcome(string $param1, string $param2) {
    if ($this->current_user->id() != 0) {
      $username = $this->current_user->getAccountName();
    }
    else {
      $username = 'Rajdip Roy.';
    }
    return [
      '#type' => 'markup',
      '#markup' => t('hello @first_name @last_name from @user',[
        '@first_name' => $param1,
        '@last_name' => $param2,
        '@user' => $username,
      ]),
      '#cache' => [
        // 'tags' => $this->current_user->getCacheTags(),
      ]
    ];
  }

}
?>
