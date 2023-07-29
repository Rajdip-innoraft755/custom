<?php

namespace Drupal\block_practice\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * FlagshipForm is to construct a config form which is to receive the data
 * regarding the flagship programmes which are showed in the flagship block.
 */
class FlagshipForm extends ConfigFormBase {


  protected $config_name = 'block_practice.flagship';

  /**
   * {@inheritDoc}
   */
  public function getEditableConfigNames() {
    return [$this->config_name];
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
    $config = $this->config($this->config_name);
    $data = $form_state->get('flagship');

    if(empty($data)) {
      $data = $config->get('flagship');
      $form_state->set('flagship', $data);
    }

    if(!$form_state->get('row_nums')) {
      if (empty($data)) {
        $form_state->set('row_nums', 1);
      }
      elseif(count($data)) {
        $form_state->set('row_nums', count($data));
      }
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
    for ($i = 1; $i <= $form_state->get('row_nums'); $i++) {
        $form['flagship'][$i]['#attributes'] = [
          'class' => ['fieldset']
        ];
        $form['flagship'][$i]['project_name'] = [
          '#type' => 'textfield',
          '#size' => 15,
          '#default_value' => $data[$i]['project_name'] ?? '',
        ];
        $form['flagship'][$i]['first_label'] = [
          '#type' => 'textfield',
          '#size' => 15,
          '#default_value' => $data[$i]['first_label'] ?? '',
        ];
        $form['flagship'][$i]['value_first_label'] = [
          '#type' => 'number',
          '#size' => 10,
          '#default_value' => $data[$i]['value_first_label'] ?? '',
        ];
        $form['flagship'][$i]['second_label'] = [
          '#type' => 'textfield',
          '#size' => 15,
          '#default_value' => $data[$i]['second_label'] ?? '',
        ];
        $form['flagship'][$i]['value_second_label'] = [
          '#type' => 'number',
          '#size' => 10,
          '#default_value' => $data[$i]['value_second_label'] ?? '',
        ];
        $form['flagship'][$i]['remove'] = [
          '#type' => 'submit',
          '#value' => $this->t('Remove'),
          '#submit' => ['::remove'],
          '#name' => $i,
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
   * This function is used for add a new row in the form
   *
   *   @param array $form
   *     Accepts the form details as the first parameter.
   *   @param FormStateInterface $form_state
   *     Accepts the FormStateInterface as the second parameter.
   */
  public function add(array &$form, FormStateInterface $form_state) {
    $form_state->set('row_nums', $form_state->get('row_nums')+1);
    $form_state->setRebuild();
  }


  /**
   * This function is used for remove a perticular new row from the form based
   * on user's chice.
   *
   *   @param array $form
   *     Accepts the form details as the first parameter.
   *   @param FormStateInterface $form_state
   *     Accepts the FormStateInterface as the second parameter.
   */
  public function remove(array &$form, FormStateInterface $form_state) {
    $trigger = $form_state->getTriggeringElement();
    $indexToRemove = $trigger['#name'];
    unset($form['flagship'][$indexToRemove]);
    $data = $form_state->getValues()['flagship'];
    unset($data[$indexToRemove]);
    $rearrage=[];
    $j=1;
    foreach($data as $key=>$item) {
      $rearrage[$j++] = $item;
    }
    $form_state->set('flagship', $rearrage);
    $form_state->setValue('flagship', $rearrage);
    $form_state->set('row_nums', $form_state->get('row_nums') - 1);
    $form_state->setRebuild();
  }

  /**
   * This function is used for sends the response to the ajaxCallback.
   *
   *   @param array $form
   *     Accepts the form details as the first parameter.
   *   @param FormStateInterface $form_state
   *     Accepts the FormStateInterface as the second parameter.
   */
  public function rebuild(array &$form, FormStateInterface $form_state) {
    return $form['flagship'];
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config($this->config_name);
    $data = $form_state->getValues()['flagship'];
    $config->set('flagship', $data);
    $config->save();
  }
}
