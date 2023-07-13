<?php

namespace Drupal\mymodule\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\mymodule\ValidateData;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * FirstForm is the class responsible to create a custom config form and takes
 * some basic inputs from user and store them after validation.
 *
 * @package Drupal\mymodule\Form
 *
 * @author Rajdip Roy <rajdip.roy@innoraft.com>
 */
class FirstForm extends ConfigFormBase {

  /**
   * @var string
   *   This is to store the configuration name.
   */
  protected $config_name = 'mymodule.settings';

  /**
   * @var \Drupal\mymodule\ValidateData
   *   This is to store the object of the ValidateData class.
   */
  protected ValidateData $validate;

  /**
   * Constructs a FirstForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\mymodule\ValidateData $validate
   *   Stores the object of ValidateData class used for input validation.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ValidateData $validate) {
    parent::__construct($config_factory);
    $this->validate = $validate;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('config.factory'),
      $container->get('mymodule.validation'),
    );
  }

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
    return [$this->config_name];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config($this->config_name);
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
    $error = $this->validate->validation($form_state->getValues());
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
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config($this->config_name);
    $config->set('full_name', $form_state->getValue('full_name'));
    $config->set('email', $form_state->getValue('email'));
    $config->set('phone_no', $form_state->getValue('phone_no'));
    $config->set('gender', $form_state->getValue('gender'));
    $config->save();
    parent::submitForm($form, $form_state);
  }
}
