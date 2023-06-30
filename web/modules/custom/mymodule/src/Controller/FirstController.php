<?php

namespace Drupal\mymodule\Controller;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Controller\ControllerBase;


/**
 * FirstController
 */
class FirstController extends ControllerBase {

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
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    if ($user->id() != 0) {
      $username = $user->getAccountName();
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
        'tags' => $user->getCacheTags(),
      ]
    ];
  }
}
?>
