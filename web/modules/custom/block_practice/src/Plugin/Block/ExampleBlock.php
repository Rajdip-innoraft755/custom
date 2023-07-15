<?php

namespace Drupal\block_practice\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "block_practice_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("Block Practice")
 * )
 */
class ExampleBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();
    $build['content'] = [
      '#markup' => $this->t('@name', ['@name' => $config['name']]),
    ];
    return $build;
  }

  /**
   * {@inheritDoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Your Name'),
      '#size' => 30,
      '#default_value' => $config['name'] ?? '',
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('name', $form_state->getValue('name'));
  }

}
