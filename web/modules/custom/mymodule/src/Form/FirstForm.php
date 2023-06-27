<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * FirstForm is the class responsible to create a custom config form and takes
 * some basic inputs from user.
 */
class FirstForm extends ConfigFormBase {

  /**
   *{@inheritDoc}
   */
  public function getFormId() {
    return 'mymodule_firstform';
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames()
  {
    return ['mymodule.settings'];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('mymodule.settings');
    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => t('Enter Your Full Name'),
      '#description' => t('Full name should contain your surname along with your first name'),
      '#description_display' => 'before',
      '#size' => 30,
      '#required' => TRUE,
      '#default_value' => $config->get('full_name'),
    ];

    $form['phone_no'] = [
      '#type' => 'tel',
      '#title' => t('Enter Your Phone number'),
      '#description' => t('Please enter indian phone number with proper country code. Ex.: +91 for India.'),
      '#description_display' => 'before',
      '#size' => 15,
      '#required' => TRUE,
      '#default_value' => $config->get('phone_no'),
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Enter Your Email Id'),
      '#description' => t('we send all the information in this email'),
      '#description_display' => 'before',
      '#size' => 30,
      '#required' => TRUE,
      '#default_value' => $config->get('email'),
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => t('Select Your Gender'),
      '#options' => [
        'male' => t('Male'),
        'female' => t('Female'),
        'others' => t('Others'),
      ],
      '#required' => TRUE,
      '#default_value' => $config->get('gender'),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit This Form'),
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form,$form_state);
    $error = $this->validateData($form_state->getValues());
    if(isset($error['full_name'])){
      $form_state->setErrorByName('full_name',$error['full_name']);
    }
    if (isset($error['email'])) {
      $form_state->setErrorByName('email', $error['email']);
    }
    if (isset($error['phone_no'])) {
      $form_state->setErrorByName('phone_no', $error['phone_no']);
    }

  }

  /**
   * This function is responsible for validating the user input data like email,
   * phone number and full name and store the errors in an array and return
   * the array.
   *
   *   @param array $values
   *     Stores the input values submitted by the user in the form as an array.
   *
   *   @return array
   *     Returs the error array after validating all of the data.
   */
  public function validateData(array $values)
  {
    $error = [];
    if (!preg_match('/^[a-zA-Z ]*$/', $values['full_name'])) {
      $error['full_name'] = 'Full name should only contain alphabets.';
    }

    $domain_name = explode('.',explode('@',$values['email'])[1])[0];
    $extension = explode('.', explode('@', $values['email'])[1])[1];
    $supported_domain_name = ['gmail','yahoo','innoraft','outlook'];

    if (!in_array($domain_name,$supported_domain_name)) {
      $error['email'] = 'Email domain is not supported.';
    }

    if ($extension != 'com') {
      $error['email'] = 'Email extension is not supported.';
    }

    if (!preg_match('/^(?:(?:\+|0{0,2})91(\s*[\-]\s*)?|[0]?)?[6789]\d{9}/',substr($values['phone_no'],3))) {
      $error['phone_no'] = 'Phone number is not a valid.';
    }
    return $error;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('mymodule.settings');
    $config->set('full_name', $form_state->getValue('full_name'));
    $config->set('email', $form_state->getValue('email'));
    $config->set('phone_no', $form_state->getValue('phone_no'));
    $config->set('gender', $form_state->getValue('gender'));
    $config->save();
    parent::submitForm($form, $form_state);
  }
}
