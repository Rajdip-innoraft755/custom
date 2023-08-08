<?php

namespace Drupal\mymodule\Form;

use Drupal;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\mymodule\ValidateData;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * FirstAjaxForm is the class responsible to create a custom config form and takes
 * some basic inputs from user and validate those through AJAX.
 *
 * @package Drupal\mymodule\Form
 *
 * @author Rajdip Roy <rajdip.roy@innoraft.com>
 */
class FirstAjaxForm extends ConfigFormBase
{

  /**
   * @var string
   *   This is to store the configuration name.
   */
  protected string $config_name = 'mymodule.ajaxsettings';

  /**
   * @var \Drupal\mymodule\ValidateData
   *   This is to store the object of the ValidateData class.
   */
  protected ValidateData $validate;

  /**
   * Constructs a FirstAjaxForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\mymodule\ValidateData $validate
   *   Stores the object of ValidateData class used for input validation.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ValidateData $validate)
  {
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
  public function getFormId()
  {
    return 'mymodule_first_ajax_form';
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
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config($this->config_name);
    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => t('Enter Your Full Name'),
      '#description' => t('Full name should contain your surname along with your first name'),
      '#description_display' => 'before',
      '#size' => 30,
      '#default_value' => $config->get('full_name'),
      '#suffix' => '<p class="error" id ="full_name"><p>',
    ];

    $form['phone_no'] = [
      '#type' => 'tel',
      '#title' => t('Enter Your Phone number'),
      '#description' => t('Please enter indian phone number with proper country code. Ex.: +91 for India.'),
      '#description_display' => 'before',
      '#size' => 15,
      '#default_value' => $config->get('phone_no'),
      '#suffix' => '<p class="error" id ="phone_no"><p>',
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Enter Your Email Id'),
      '#description' => t('we send all the information in this email'),
      '#description_display' => 'before',
      '#size' => 30,
      '#default_value' => $config->get('email'),
      '#suffix' => '<p class="error" id ="email"><p>',
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => t('Select Your Gender'),
      '#options' => [
        'male' => t('Male'),
        'female' => t('Female'),
        'others' => t('Others'),
      ],
      '#default_value' => $config->get('gender'),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit This Form'),
      '#ajax' => [
        'callback' => '::validateWithAjax',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ],
      '#suffix' => '<p class="success"><p>',
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
  }

  /**
   * This Function is used to call the validation function on ajax call and
   * sends back the response after adding proper messages to the callback.
   *
   * @param array $form
   *   Takes the $form as argument.
   * @param FormStateInterface $form_state
   *   Takes the FormStateInterface variable to retreive the input data.
   *
   * @return AjaxResponse
   *   Returns AjaxResponse to the callback.
   */
  public function validateWithAjax(array &$form, FormStateInterface $from_state) {
    $error = $this->validate->validation($from_state->getValues());
    $response = new AjaxResponse();
    $response->addCommand(new CssCommand('.error', ['color' => 'red',]));
    $response->addCommand(new HtmlCommand('.error', ''));
    $response->addCommand(new HtmlCommand('.success', ''));

    if($error) {
      foreach ($error as $key => $value) {
        $response->addCommand(new HtmlCommand('#' . $key, $value));
      }
    }
    else {
      $response->addCommand(new HtmlCommand('.success', 'Form Submitted Successfully'));
      $response->addCommand(new CssCommand('.success', ['color' => 'green',]));
      $this->submitFormWithAjax($form, $from_state);
    }
    return $response;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }

  /**
   * This Function is used to save the configouration based on valid input of
   * required data.
   *
   * @param array $form
   *   Takes the $form as argument.
   * @param FormStateInterface $form_state
   *   Takes the FormStateInterface variable to retreive the input data.
   *
   * @return array
   *   Returns the $form array.
   */
  public function submitFormWithAjax(array &$form, FormStateInterface $form_state) {
    $config = $this->config($this->config_name);
    $config->set('full_name', $form_state->getValue('full_name'));
    $config->set('email', $form_state->getValue('email'));
    $config->set('phone_no', $form_state->getValue('phone_no'));
    $config->set('gender', $form_state->getValue('gender'));
    $config->save();
    return $form;
  }
}
