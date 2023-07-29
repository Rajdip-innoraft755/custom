<?php

namespace Drupal\entity_practice;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a listing of movies.
 */
class MovieListBuilder extends ConfigEntityListBuilder {

  /**
   * This is to store the EntityTypeManagerInterface instance.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManagerInterface;

  /**
   * {@inheritDoc}
   *
   * Here we edit the createInstance fuction of EntityListBuilder class
   * basically pass the entityTypeManagerInterface instance directly to the
   * constructor instead of EntityStorageInterface instance.
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Constructs a new MovieListBuilder object.
   *
   * In this case we reuse the EntityListBuilder class constructor with some
   * required changes.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The EntityTypeManagerInterface instance.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeId = $entity_type->id();
    $this->storage = $entity_type_manager->getStorage($this->entityTypeId);
    $this->entityType = $entity_type;
    $this->entityTypeManagerInterface = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['award_name'] = $this->t('AWARD NAME');
    $header['year'] = $this->t('YEAR');
    $header['movie_name'] = $this->t('AWARD WINNING MOVIE NAME');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\entity_practice\MovieInterface $entity */
    $movie = $this->entityTypeManagerInterface->getStorage('node')->load($entity->get('movie_name') ?? '');
    $row['award_name'] = $entity->label();
    $row['year'] = $entity->get('year');
    $row['movie_name'] = $movie ? $movie->toLink() : '';
    return $row + parent::buildRow($entity);
  }

}
