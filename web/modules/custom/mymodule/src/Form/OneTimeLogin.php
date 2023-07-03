<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * OneTimeLogin class is to vefrify the user id and geneate the one time login
 * link for that user.
 *
 * @package Drupal\mymodule\Form
 *
 * @author Rajdip Roy <rajdip.roy@innoraft.com>
 */
class OneTimeLogin extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'one_time_login';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_id'] = [
      '#title' => t('enter the user id'),
      '#type' => 'number',
      '#description' => 'Enter the user id of the user of which you want to get the OTTL.',
      '#suffix' => '<p class="message error" id="user_id"></p>',
      '#size' => 3,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => t('get the link'),
      '#ajax' => [
        'callback' => '::getLink',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Generating OTTL...'),
        ],
      ],
      '#suffix' => '<p class="message" id="link"><p>',
    ];

    return $form;
  }

  /**
   * This Function is used to generate the one time login link for the valid
   * user id and give proper message if an invalid user id given and sends
   * back the response after adding proper messages to the callback.
   *
   *   @param array $form
   *     Takes the $form as argument.
   *   @param FormStateInterface $form_state
   *     Takes the FormStateInterface variable to retreive the input data.
   *
   *   @return AjaxResponse
   *     Returns AjaxResponse to the callback.
   */
  public function getLink(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $uid = $form_state->getValue('user_id');
    $response->addCommand(new HtmlCommand('.message', ''));
    $response->addCommand(new CssCommand('.error', ['color' => 'red']));
    if (!$uid) {
      $response->addCommand(new HtmlCommand('#user_id', 'Please Enter User ID other than 0.'));
    }
    else {
      $user = User::load($uid);
      if (!$user) {
        $response->addCommand(new HtmlCommand('#user_id', 'Invalid User ID.'));
      }
      elseif (user_is_blocked($user->getAccountName())) {
        $response->addCommand(new HtmlCommand('#user_id', 'User is blocked.'));
      }
      else {
        $link = user_pass_reset_url($user) . '/login';
        $response->addCommand(new HtmlCommand('#link', '<a href= "' . $link . '"> Login with this link'));
      }
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
}
?>
