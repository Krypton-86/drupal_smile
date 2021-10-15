<?php

namespace Drupal\services_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Services' block.
 *
 * @Block(
 *   id = "service_block",
 *   admin_label = @Translation("Services"),
 *   category = @Translation("Services")
 * )
 */
class ServicesBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $query = \Drupal::entityQuery('node');
    $entity_ids = $query->condition('type', 'services', '=')
      ->range(0, 3)
      ->execute();
    $entity_type_manager = \Drupal::entityTypeManager();
    $node_view_builder = $entity_type_manager->getViewBuilder('node');

    foreach ($entity_ids as $id) {
      $node = $entity_type_manager->getStorage('node')->load($id);
      $list['services'][$node->id()] = $node_view_builder->view($node, 'teaser');
    }

    $d = 'd';

    return [
      '#theme' => 'services_theme',
      '#list' => $list,
    ];
  }

}
