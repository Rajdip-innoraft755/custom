<?php

namespace Drupal\block_practice\Plugin\Block;

use Drupal;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an Flagship Progammes block, it passes the data to the
 * flagship-block.html.twig file and rendered that page.
 *
 * @Block(
 *   id = "block_practice_flagship",
 *   admin_label = @Translation("Flagship Progammes"),
 *   category = @Translation("Block Practice")
 * )
 */
class FlagshipBlock extends BlockBase implements BlockPluginInterface
{

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $config = Drupal::config('block_practice.flagship');
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
