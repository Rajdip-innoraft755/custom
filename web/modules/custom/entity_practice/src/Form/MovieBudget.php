<?php

namespace Drupal\entity_practice\Form;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This form is to take the movie budget to catagorize the movies based on it.
 */
class MovieBudget extends ConfigFormBase {

  /**
   * This is to store the CacheTagInvalidatorInterface instance.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected CacheTagsInvalidatorInterface $cacheTagInvalidator;
  /**
   * This is to store the configuration name.
   *
   * @var string
   */
  protected string $configName = 'entity_practice.budget';

  /**
   * This will construct the object of MovieBudget form.
   *
   * This constructer basically used for initialize the required services for
   * this form handler.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   The ConfigFactoryInterface instance.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tag_invalidator
   *   The CacheTagInvalidatorInterface instance.
   */
  public function __construct(ConfigFactoryInterface $config, CacheTagsInvalidatorInterface $cache_tag_invalidator) {
    $this->cacheTagInvalidator = $cache_tag_invalidator;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('cache_tags.invalidator'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'entity_practice_budget_form';
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
    return [$this->configName];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config($this->configName);
    $form['budget'] = [
      '#type' => 'number',
      '#title' => $this->t('Enter Your Movie Budget'),
      '#description' => $this->t('This is the budget based on which we catagorize the movies.'),
      '#size' => 10,
      '#field_prefix' => '$',
      '#min' => 0,
      '#required' => TRUE,
      '#default_value' => $config->get('budget'),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save The Budget'),
    ];
    $form['#cache'] = [
      'tags' => ['entity_practice.budget'],
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config($this->configName);
    $config->set('budget', $form_state->getValue('budget'));
    $this->cacheTagInvalidator->invalidateTags($config->getCacheTags());
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
