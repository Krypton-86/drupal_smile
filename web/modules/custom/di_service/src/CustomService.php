<?php

namespace Drupal\di_service;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Implement custom service.
 */
class CustomService {
  use MessengerTrait;
  use StringTranslationTrait;

  protected $connection;
  protected $currentUser;
  protected $entityTypeManager;

  /**
   * Implements dependency injection.
   */
  public function __construct(
    Connection $connection,
  TranslationInterface $translation,
  AccountInterface $currentUser,
  EntityTypeManagerInterface $entityTypeManager,
  MessengerInterface $messenger) {

    $this->connection = $connection;
    $this->setStringTranslation($translation);
    $this->currentUser = $currentUser;
    $this->entityTypeManager = $entityTypeManager;
    $this->setMessenger($messenger);
  }

  /**
   * Get`s amount of all active users.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   */
  public function getAmountOfAllActive() {
    try {
      $result = $this->connection->select('users_field_data', 'us')
        ->condition('status', '1', '=')
        ->fields('us', ['status'])
        ->execute();
      $record = $result->fetchAll();
      $row = count($record);
      return $this->t('You are unique among @count users', ['@count' => $row]);
    }
    catch (\Exception $e) {
      $this->messenger()->addMessage($this->t('Select failed. Message = %message', [
        '%message' => $e->getMessage(),
      ]), 'error');
    }

  }

  /**
   * Get`s current user position in users table.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   */
  public function getCurrentUserPosition() {
    $result = $this->connection->select('users_field_data', 'us')
      ->condition('status', '1', '=')
      ->fields('us', ['uid', 'created'])
      ->orderBy('created', 'ASC')
      ->execute();
    $record = $result->fetchAll();
    $count = 0;
    foreach ($record as $row) {
      $id = $row->uid;
      $id2 = $this->getData();
      $count++;
      if ($id == $id2) {
        break;
      }
    };
    return $this->t('Your position of registration @number ', ['@number' => $count]);
  }

  /**
   * Get`s node render.
   */
  public function getNode() {
    $nodes = $this->entityTypeManager->getStorage('node')
      ->loadMultiple();
    $id_node = array_rand($nodes, 1);
    $node = $this->entityTypeManager->getStorage('node')->load($id_node);
    $result = $this->entityTypeManager->getViewBuilder('node')->view($node, 'teaser');
    return $result;
  }

  /**
   * Get`s user id.
   */
  public function getData() {
    return $this->currentUser->id();
  }

  /**
   * Get`s user data (last visit, username).
   */
  public function getUserData() {
    $date = date('F j, Y, G:i:s', $this->currentUser->getLastAccessedTime());
    return [
      'date' => $this->t('Last visit: @date', ['@date' => $date]),
      'name' => $this->t('Login: @username', ['@username' => $this->currentUser->getAccountName()]),
    ];
  }

}
