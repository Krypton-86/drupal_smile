<?php

namespace Drupal\pets_owners_rest\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @RestResource(
 *   id = "pets_owners_list_resource",
 *   label = @Translation("Pets owners list"),
 *   uri_paths = {
 *     "canonical" = "/api/pets_owners/list",
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
   * List all pets owners.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $page = \Drupal::request()->get('page') <= 0 ? 1 : \Drupal::request()->get('page');
    $limit = \Drupal::request()->get('limit') <= 0 ? 2 : \Drupal::request()->get('limit');
    $age = \Drupal::request()->get('age');

    $result = \Drupal::database()
      ->select('pets_owners_storage', 'st')
      ->fields('st')
      ->orderBy('st.id', 'ASC');
    if (!empty($age)) {
      $result = $result->condition('st.age', $age);
    }
    $result = $result->range(($page - 1) * $limit, $limit)
      ->execute()->fetchAllAssoc('id');

    foreach ($result as $item) {
      $list['data'][$item->id] = (array) $item;
    }
    $list['config']['pager'] = [
      'page' => $page,
      'pages' => $this->countPages($age, $limit),
    ];
    if (!empty($list)) {
      return new ResourceResponse($list);
    }
    throw new NotFoundHttpException(t('Pets owners list is empty!'));
  }

  /**
   * Returns count of pages by limit.
   */
  public function countPages($age, $limit) {
    $result = \Drupal::database()
      ->select('pets_owners_storage', 'st')
      ->fields('st')
      ->countQuery();
    if (!empty($age)) {
      $result = $result->condition('st.age', $age);
    }
    $result = $result->execute()->fetchAssoc();
    $records = $result['expression'];
    $limit = $limit > $records ? $records : $limit;
    return $records % $limit > 0 ? intval($records / $limit) + 1 : intval($records / $limit);
  }

}
