<?php

namespace Drupal\cron_batch\Plugin\QueueWorker;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Process a queue.
 *
 * @QueueWorker(
 *   id = "db_logger",
 *   title = @Translation("Custom logger queue"),
 *   cron = {"time" = 45}
 * )
 */
class DBLoggerQueueWorker extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  /**
   * The logger channel the action will write log messages to.
   *
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * DBLoggerQueueWorker constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Core\Logger\LoggerChannelInterface $logger
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityManager
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              LoggerChannelInterface $logger,
                              EntityTypeManagerInterface $entityManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->logger = $logger;
    $this->entityManager = $entityManager;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('logger.factory')->get('cron_batch'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * @inheritDoc
   */
  public function processItem($data) {
    $queue = \Drupal::queue('db_logger');
    $queue->createQueue();
    $users = $this->entityManager
      ->getStorage('user')
      ->getQuery()
      ->condition('status', 1)
      ->condition('roles', 'administrator')
      ->execute();

    foreach ($users as $uid) {
      $user = $this->entityManager
        ->getStorage('user')
        ->load($uid);
      $this->logger->notice("User @username should be notified about new node ‘@node_title[@node_id]", [
        '@username' => $user->getAccountName(),
        '@node_title' => $data['title'],
        '@node_id' => $data['id'],
      ]);
    }
  }

}
