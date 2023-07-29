<?php

namespace Drupal\entity_practice\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MovieForm is to display the form at the time to add a new Movie.
 *
 * @property \Drupal\entity_practice\MovieInterface $entity
 */
class MovieForm extends EntityForm {

  /**
   * This is to store the EntityTypeManagerInterface instance.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManagerInterface;

  /**
   * This constructor is used to initialize the object of MovieForm.
   *
   * In this constructor we initialize the required instance of entity type
   * manager.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The EntityTypeManagerInterface instance.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManagerInterface = $entity_type_manager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    if (!$this->entity->isNew()) {
      $movie_name = $this->entityTypeManagerInterface->getStorage('node')
        ->load($this->entity->get('movie_name'));
    }
    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Award Title'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Title of the award'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\entity_practice\Entity\Movie::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];

    $form['movie_name'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#selection_settings' => ['target_bundles' => ['movie']],
      '#title' => $this->t('Movie Name'),
      '#default_value' => $movie_name ?? '',
      '#description' => $this->t('Name of the award winning movie'),
    ];

    $form['year'] = [
      '#type' => 'date',
      '#title' => $this->t('Year'),
      '#default_value' => $this->entity->get('year'),
      '#description' => $this->t('Award winning year.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->save();

    $message_args = ['%label' => $this->entity->label()];
    $message = $this->entity->isNew()
      ? $this->t('Created new movie %label.', $message_args)
      : $this->t('Updated movie %label.', $message_args);
    $this->messenger()->addStatus($message);

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
  }

}
