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

}
