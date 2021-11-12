<?php

namespace Drupal\events\Event;

use Drupal\Core\Entity\EntityInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 *
 */
class NodeEvent extends Event {

  /**
   * Save node event.
   */
  const NODE_SAVED = 'events.node_saved';

  /**
   * Current node.
   *
   * @var EntityInterface
   */
  protected $node;

  /**
   * @param EntityInterface $node
   */
  public function __construct($node) {
    $this->node = $node;
  }

  /**
   * Get curent node.
   *
   * @return EntityInterface
   */
  public function getNode() {
    return $this->node;
  }

}
