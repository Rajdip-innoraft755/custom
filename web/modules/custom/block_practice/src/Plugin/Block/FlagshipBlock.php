<?php

namespace Drupal\block_practice\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an Flagship Progammes block.
 *
 * It passes the data to the flagship-block.html.twig file and
 * rendered that page.
 *
 * @Block(
 *   id = "block_practice_flagship",
 *   admin_label = @Translation("Flagship Progammes"),
 *   category = @Translation("Block Practice")
 * )
 */
class FlagshipBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * This is store the instance of \Drupal\Core\Config\ConfigFactoryInterface.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Constructs a FlagshipBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config
   *   This is for accepting the instance of ConfigFactoryInterface.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->config->getEditable('block_practice.flagship');
    $data = $config->get('flagship');
    $build['content'] = [
      '#theme' => 'flagship_block',
      '#data' => $data,
      '#attached' => [
        'library' => ['block_practice/flagship-block'],
      ],
    ];
    return $build;
  }

}
