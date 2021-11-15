<?php

namespace Drupal\smile_entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the smile entity entity type.
 */
class SmileEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view smile entity');

      case 'update':
        return AccessResult::allowedIfHasPermissions($account, ['edit smile entity', 'administer smile entity'], 'OR');

      case 'delete':
        return AccessResult::allowedIfHasPermissions($account, ['delete smile entity', 'administer smile entity'], 'OR');

      default:
        // No opinion.
        return AccessResult::neutral();
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermissions($account, ['create smile entity', 'administer smile entity'], 'OR');
  }

}
