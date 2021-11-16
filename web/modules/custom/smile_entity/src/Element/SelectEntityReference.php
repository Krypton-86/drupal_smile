<?php

namespace Drupal\smile_entity\Element;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Select;

/**
 * Select drop-down for selecting reference entity.
 * http://borutpiletic.com/article/select-entity-reference-drop-down-element
 * @FormElement("select_entity_reference")
 */
class SelectEntityReference extends Select {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    return parent::getInfo() + [
      '#target_type' => NULL,
      '#option_settings' => [
        'label' => 'Role',
        'value' => 'role_code',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function processSelect(&$element, FormStateInterface $form_state, &$complete_form) {
    $element = parent::processSelect($element, $form_state, $complete_form);
    $entities = static::getEntityTypeManager()
      ->getStorage($element['target_type'])
      ->loadMultiple();
    $optionLabel = $element['option_settings']['label'];
    $optionValue = $element['option_settings']['value'];

    foreach ($entities as $entity) {
      if ($entity instanceof ContentEntityInterface) {
        /** @var \Drupal\Core\Entity\ContentEntityInterface $entity */
        if (!$entity->hasField($optionLabel) || !$entity->hasField($optionValue)) {
          throw new \Exception('Target entity has non-existing field defined for option value and/or label in (#option_settings).');
        }
        $element['#options'][$entity->get($optionValue)->getString()] = $entity->get($optionLabel)->getString();
      }
      if ($entity instanceof ConfigEntityInterface) {
        $element['#options'][$entity->get($optionValue)] = $entity->get($optionLabel);
      }
    }
    return $element;
  }

  /**
   * Wraps entity type manager.
   *
   * @return \Drupal\Core\Entity\EntityTypeManagerInterface
   *   Entity type manager.
   */
  protected static function getEntityTypeManager() {
    return \Drupal::service('entity_type.manager');
  }

}
