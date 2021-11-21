<?php

namespace Drupal\smile_entity\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\smile_entity\SmileEntityInterface;

/**
 * Defines the smile entity entity class.
 *
 * @ContentEntityType(
 *   id = "smile_entity",
 *   label = @Translation("Smile Entity"),
 *   label_collection = @Translation("Smile Entities"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\smile_entity\SmileEntityListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\smile_entity\SmileEntityAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\smile_entity\Form\SmileEntityForm",
 *       "edit" = "Drupal\smile_entity\Form\SmileEntityForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "smile_entity",
 *   admin_permission = "access smile entity overview",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/smile/add",
 *     "canonical" = "/smile/{smile_entity}",
 *     "edit-form" = "/smile/{smile_entity}/edit",
 *     "delete-form" = "/smile/{smile_entity}/delete",
 *     "collection" = "/smile"
 *   },
 * )
 */
class SmileEntity extends ContentEntityBase implements SmileEntityInterface {

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the smile entity entity.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 1,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['body'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Body'))
      ->setDescription(t('A body of the smile entity.'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'text_default',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'text_default',
        'label' => 'above',
        'weight' => 2,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['role'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel('Role')
      ->setDescription('Only selected role will have access to entity page.')
      ->setSetting('target_type', 'user_role')
      ->setDisplayOptions('form', [
        'type' => 'options_buttons',
      ])
      ->setRequired(TRUE)
      ->setDisplayConfigurable('form', TRUE);
    return $fields;
  }

}
