<?php

namespace Drupal\event_practice\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\node\Entity\Node;

/**
 * Event that is fired when a node is viewed.
 */
class NodeViewEvent extends Event {

  const NODE_VIEW = 'event_practice.node_view';

  /**
   * The current node.
   *
   * @var \Drupal\node\Entity\Node
   */
  public Node $node;

  /**
   * Constructs the object.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node.
   */
  public function __construct(Node $node) {
    $this->node = $node;
  }

}
