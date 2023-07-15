<?php

namespace Drupal\block_practice\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * FlagshipForm is a config form for receiving the data for flagship programmes.
 */
class FlagshipForm extends ConfigFormBase {

  /**
   * Undocumented variable.
   *
   * @var string
   */
  protected $configName = 'block_practice.flagship';

  /**
   * {@inheritDoc}
   */
  public function getEditableConfigNames() {
    return [$this->configName];
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'block_practice_flagship';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config($this->configName);
    $data = $form_state->get('flagship');

    if (empty($data)) {
      $data = $config->get('flagship');
      $form_state->set('flagship', $data);
    }

    // If the data fetched from the config table is also empty then
    // it adds a new blank row in the form.
    if (empty($data)) {
      $this->add($form, $form_state);
    }

    $form['flagship'] = [
      '#type' => 'table',
      '#caption' => $this->t('FLAGSHIP PROGRAMEES FORM'),
      '#header' => [
        $this->t('Project Name'),
        $this->t('first Label'),
        $this->t('Value of first Label'),
        $this->t('second Label'),
        $this->t('Value of second Label'),
        $this->t('Remove'),
      ],
      '#prefix' => '<div id="field-wrapper">',
      '#suffix' => '</div>',
    ];
    foreach ($data as $key => $value) {
      $form['flagship'][$key]['#attributes'] = [
        'class' => ['fieldset'],
      ];
      $form['flagship'][$key]['project_name'] = [
        '#type' => 'textfield',
        '#size' => 15,
        '#default_value' => $value['project_name'] ?? '',
      ];
      $form['flagship'][$key]['first_label'] = [
        '#type' => 'textfield',
        '#size' => 15,
        '#default_value' => $value['first_label'] ?? '',
      ];
      $form['flagship'][$key]['value_first_label'] = [
        '#type' => 'number',
        '#size' => 10,
        '#default_value' => $value['value_first_label'] ?? '',
      ];
      $form['flagship'][$key]['second_label'] = [
        '#type' => 'textfield',
        '#size' => 15,
        '#default_value' => $value['second_label'] ?? '',
      ];
      $form['flagship'][$key]['value_second_label'] = [
        '#type' => 'number',
        '#size' => 10,
        '#default_value' => $value['value_second_label'] ?? '',
      ];
      $form['flagship'][$key]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::remove'],
        '#name' => $key,
        '#ajax' => [
          'callback' => '::rebuild',
          'wrapper' => 'field-wrapper',
        ],
      ];

    }
    $form['actions']['addmore'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add One More'),
      '#submit' => ['::add'],
      '#ajax' => [
        'callback' => '::rebuild',
        'wrapper' => 'field-wrapper',
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * This function is used for add a new row in the form.
   *
   * @param array $form
   *   Accepts the form details as the first parameter.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Accepts the FormStateInterface as the second parameter.
   */
  public function add(array &$form, FormStateInterface $form_state) {
    $add_one = $form_state->get('flagship');
    $add_one[] = [
      'project_name' => '',
      'first_label' => '',
      'value_first_label' => '',
      'second_label' => '',
      'value_second_label' => '',
    ];
    $form_state->set('flagship', $add_one);
    $form_state->setRebuild();
  }

  /**
   * This is used for remove a perticular new row from the form.
   *
   * This function removes the row based on user selection and it also
   * add one row if user delete all the rows.
   *
   * @param array $form
   *   Accepts the form details as the first parameter.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Accepts the FormStateInterface as the second parameter.
   */
  public function remove(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $index_to_remove = $trigger['#name'];
    unset($form['flagship'][$index_to_remove]);
    $data = $form_state->getValues()['flagship'];
    unset($data[$index_to_remove]);
    $form_state->set('flagship', $data);
    if (empty($form_state->get('flagship'))) {
      $this->add($form, $form_state);
    }
    $form_state->setRebuild();
  }

  /**
   * This function is used for sends the response to the ajaxCallback.
   *
   * @param array $form
   *   Accepts the form details as the first parameter.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Accepts the FormStateInterface as the second parameter.
   */
  public function rebuild(array &$form, FormStateInterface $form_state) {
    return $form['flagship'];
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config($this->configName);
    $data = $form_state->getValues()['flagship'];
    $config->set('flagship', $data);
    $config->save();
  }

}
