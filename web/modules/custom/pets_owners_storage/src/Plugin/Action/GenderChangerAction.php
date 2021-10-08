<?php

namespace Drupal\pets_owners_storage\Plugin\Action;
use Drupal\views_bulk_operations\Action\ViewsBulkOperationsActionBase;
use Drupal\Core\Entity\TranslatableInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Change gender.
 *
 * @Action(
 *   id = "views_bulk_operations_change_gender",
 *   label = @Translation("Change selected entities / translations"),
 *   type = "",
 *   confirm = TRUE,
 * )
 */
class ChangeGenderAction extends ViewsBulkOperationsActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    if ($entity instanceof TranslatableInterface && !$entity->isDefaultTranslation()) {
      $untranslated_entity = $entity->getUntranslated();
      $untranslated_entity->removeTranslation($entity->language()->getId());
      $untranslated_entity->save();
      return $this->t('Delete translations');
    }
    else {
      $entity->delete();
      return $this->t('Delete entities');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $access = $object->access('administer pets_owners_storage', $account, TRUE);
    if ($object->getEntityType() === 'node') {
      $access->andIf($object->status->access('administer pets_owners_storage', $account, TRUE));
    }
    return $return_as_object ? $access : $access->isAllowed();
  }

}
