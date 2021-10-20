<?php

namespace Drupal\smile_test;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Client entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup smile_test
 */
interface ClientInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
