<?php

namespace Drupal\entity_practice\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\entity_practice\MovieInterface;

/**
 * Defines the movie entity type.
 *
 * @ConfigEntityType(
 *   id = "movie",
 *   label = @Translation("Movie"),
 *   label_collection = @Translation("Movies"),
 *   label_singular = @Translation("movie"),
 *   label_plural = @Translation("movies"),
 *   label_count = @PluralTranslation(
 *     singular = "@count movie",
 *     plural = "@count movies",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\entity_practice\MovieListBuilder",
 *     "form" = {
 *       "add" = "Drupal\entity_practice\Form\MovieForm",
 *       "edit" = "Drupal\entity_practice\Form\MovieForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "movie",
 *   admin_permission = "administer movie",
 *   links = {
 *     "collection" = "/admin/structure/movie",
 *     "add-form" = "/admin/structure/movie/add",
 *     "edit-form" = "/admin/structure/movie/{movie}",
 *     "delete-form" = "/admin/structure/movie/{movie}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "movie_name" = "movie_name",
 *     "year" = "year"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "movie_name",
 *     "year"
 *   }
 * )
 */
class Movie extends ConfigEntityBase implements MovieInterface {

  /**
   * The movie ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The movie label.
   *
   * @var string
   */
  protected $label;

  /**
   * The movie status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The movie name.
   *
   * @var object
   */
  protected $movieName;

  /**
   * The award winning year.
   *
   * @var string
   */
  protected $year;

}
