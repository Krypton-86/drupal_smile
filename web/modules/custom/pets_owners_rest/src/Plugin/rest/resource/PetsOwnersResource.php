<?php

namespace Drupal\pets_owners_rest\Plugin\rest\resource;

use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @RestResource(
 *   id = "pets_owners_resource",
 *   label = @Translation("Pets owners CRUD"),
 *   uri_paths = {
 *     "canonical" = "/api/pets_owners",
 *     "create" = "/api/pets_owners/edit"
 *   }
 * )
 */
class PetsOwnersResource extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * Implements loading specific record by id.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $id = \Drupal::request()->get('id');
    if ($id > 0) {
      $result = \Drupal::database()
        ->select('pets_owners_storage', 'st')
        ->fields('st', [
          'id',
          'name',
          'gender',
          'prefix',
          'age',
          'mother_name',
          'father_name',
          'have_pets',
          'pets_name',
          'email',
        ])
        ->condition('id', $id)
        ->execute()
        ->fetchAssoc();
      if (!empty($result)) {
        return new ResourceResponse($result);
      }
      throw new NotFoundHttpException(t('Pets owner with ID :id was not found', [':id' => $id]));
    }
    throw new BadRequestHttpException(t('No entry ID was provided'));
  }

  /**
   * Implements editing specific record by id.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function post($data) {
    $fields = [
      'name'        => $data['name'],
      'gender'      => $data['gender'],
      'prefix'      => $data['prefix'],
      'age'         => $data['age'],
      'mother_name' => $data['mother_name'],
      'father_name' => $data['father_name'],
      'have_pets'   => $data['have_pets'],
      'pets_name'   => $data['pets_name'],
      'email'       => $data['email'],
    ];
    try {
      $result = \Drupal::database()
        ->update('pets_owners_storage')
        ->fields($fields)
        ->condition('id', $data['id'])
        ->execute();
      if ($result == TRUE) {
        return new ModifiedResourceResponse(NULL, 200);
      }
      else {
        return new ModifiedResourceResponse(NULL, 400);
      }
    }
    catch (\Exception $e) {
      throw new HttpException(500, 'Internal Server Error', $e);
    }
  }

  /**
   * Implements deleting specific record by id.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function delete() {
    $id = \Drupal::request()->get('id');
    if ($id > 0) {
      try {
        $result = \Drupal::database()
          ->delete('pets_owners_storage')
          ->condition('id', $id)
          ->execute();
        if ($result == TRUE) {
          return new ModifiedResourceResponse(NULL, 200);
        }
        else {
          return new ModifiedResourceResponse(NULL, 400);
        }
      }
      catch (\Exception $e) {
        throw new HttpException(500, 'Internal Server Error', $e);
      }
    }
    return new ModifiedResourceResponse(NULL, 400);
  }

}
