<?php

namespace Drupal\pets_owners_REST\Plugin\rest\resource;

use Drupal\Core\Database\Database;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\ultimate_cron\Logger\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

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
   * Implements GET requests with parameter pid.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function get($pid = NULL) {
    if ($pid > 0) {
      $query = Database::getConnection()
        ->select('pets_owners_storage', 's')
        ->condition('s.pid', $pid)
        ->fields('s')
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
   * Implements POST requests.
   *
   * @return \Drupal\rest\ResourceResponse
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

  /**
   * Implements DELETE requests.
   *
   * @return \Drupal\rest\ResourceResponse
   */
  public function delete($pid): ModifiedResourceResponse|ResourceResponse {
    if ($pid > 0) {
      try {
        $query = \Drupal::database();
        $result = $query->delete('pets_owners_storage')
          ->condition('pid', $pid)
          ->execute();
        if ($result == TRUE) {
          return new ModifiedResourceResponse(NULL, 204);
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
