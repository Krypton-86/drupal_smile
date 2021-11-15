<?php

namespace Drupal\smile_entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining a smile entity entity type.
 */
interface SmileEntityInterface extends ContentEntityInterface {

  /**
   * Gets the smile entity title.
   *
   * @return string
   *   Title of the smile entity.
   */
  public function getTitle();

  /**
   * Sets the smile entity title.
   *
   * @param string $title
   *   The smile entity title.
   *
   * @return \Drupal\smile_entity\SmileEntityInterface
   *   The called smile entity entity.
   */
  public function setTitle($title);

}
