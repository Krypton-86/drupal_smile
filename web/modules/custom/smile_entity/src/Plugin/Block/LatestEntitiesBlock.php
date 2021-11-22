<?php

namespace Drupal\smile_entity\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a latest_entities block.
 *
 * @Block(
 *   id = "smile_entity_latest_entities",
 *   admin_label = @Translation("Latest_entities"),
 *   category = @Translation("Custom")
 * )
 */
class LatestEntitiesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs a new LatestEntitiesBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current user.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'items' => 10,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['form_items'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Count of latest entities'),
      '#default_value' => $this->configuration['items'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['items'] = $form_state->getValue('form_items');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $entity_type_id = 'smile_entity';
    $query = $this->entityTypeManager->getStorage($entity_type_id)->getQuery();
    $entity_ids = $query
      ->condition('role', $this->currentUser->getRoles(), 'IN')
      ->sort("created", "DESC")
      ->range(0, $this->configuration['items'])
      ->execute();
    $build = [];
    $smile_entities = $this->entityTypeManager
      ->getStorage($entity_type_id)
      ->loadMultiple($entity_ids);
    $build[] = $this->entityTypeManager
      ->getViewBuilder($entity_type_id)
      ->viewMultiple($smile_entities);
    return [
      'elements'     => $build,
      '#cache' => [
        'contexts' => ['user.roles'],
      ],
    ];
  }

}
