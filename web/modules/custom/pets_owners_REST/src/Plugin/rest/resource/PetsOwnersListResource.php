<?php

namespace Drupal\pets_owners_REST\Plugin\rest\resource;

use Drupal\Core\Database\Database;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\ultimate_cron\Logger\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @RestResource(
 *   id = "pets_owners_list_resource",
 *   label = @Translation("Rest list of pets owners"),
 *   uri_paths = {
 *     "canonical" = "/api/pets_owners",
 *   }
 * )
 */
class PetsOwnersListResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
          $plugin_id,
          $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

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
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('pets_owners_REST'),
      $container->get('current_user')
    );
  }

  /**
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $page = \Drupal::request()->get('page');
    if ($page <= 0) {
      $page = 1;
    }
    $limit = \Drupal::request()->get('limit');
    if ($limit <= 0) {
      $limit = 2;
    }
    $name = \Drupal::request()->get('name') . '%';
    $query = Database::getConnection()
      ->select('pets_owners_storage', 's')
      ->fields('s')->orderBy('s.pid', 'ASC');
    if (!empty($name)) {
      $query = $query->condition('s.name', $name, 'LIKE');
    }
    $query = $query->range(($page - 1) * $limit, $limit)
      ->execute()->fetchAllAssoc('pid');
    // Object to array.
    foreach ($query as $item) {
      $list[$item->pid] = (array) $item;
    }
    if (!empty($list)) {
      return new ResourceResponse($list);
    }
    throw new NotFoundHttpException(t('Pets owners list is empty!'));
  }

}
