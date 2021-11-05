<?php

namespace Drupal\pets_owners_REST\Plugin\rest\resource;

use Drupal\Core\Database\Database;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @RestResource(
 *   id = "pets_owners_resource",
 *   label = @Translation("Endpoint GET"),
 *   uri_paths = {
 *     "canonical" = "/api/pets_owners/{pid}",
 *     "create" = "/api/pets_owners/{pid}",
 *   }
 * )
 */
class PetsOwnersResource extends ResourceBase {

  /**
   * Responds to GET requests.
   *
   * @return ResourceResponse
   */
  public function get($pid = NULL) {
    if ($pid > 0) {
      $query = Database::getConnection()
        ->select('pets_owners_storage', 'p')
        ->condition('p.pid', $pid)
        ->fields('p')
        ->execute()
        ->fetchAssoc();
      if (!empty($query)) {
        return new ResourceResponse($query);
      }
      throw new NotFoundHttpException(t('Pets owner with PID :pid was not found', [':pid' => $pid]));
    }
    throw new BadRequestHttpException(t('No entry PID was provided'));
  }
  /**
   * Responds to POST requests.
   *
   * @return ResourceResponse
   */
  public function post($pid, $data) {
    // Array of fields from DB.
    $fields = [
      'prefix' => 'prefix',
      'name' => 'name',
      'gender' => 'gender',
      'age' => 'age',
      'father' => 'father',
      'mother' => 'mother',
      'pets_name' => 'pets_mame',
      'email' => 'email',
    ];
    $value = array_intersect_key($data, $fields);
    if ($pid > 0 && !empty($value)) {
      try {
        $query = \Drupal::database();
        $update = $query->update('pets_owners_storage')
          ->fields($value)
          ->condition('pid', $pid)
          ->execute();
        if ($update == TRUE) {
          return new ResourceResponse('Record was updated in DB');
        }
      }
      catch (\Exception $e) {
        throw new HttpException(500, 'Internal Server Error', $e);
      }
      throw new NotFoundHttpException(t('Pets owner with PID :pid was not found', [':pid' => $pid]));
    }
    throw new BadRequestHttpException(t('No entry PID or query parameters was provided'));
  }

}
