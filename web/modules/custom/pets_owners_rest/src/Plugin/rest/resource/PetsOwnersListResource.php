<?php

namespace Drupal\pets_owners_rest\Plugin\rest\resource;

use Drupal\Core\Database\Database;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @RestResource(
 *   id = "pets_owners_list_resource",
 *   label = @Translation("Pets owners list"),
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
