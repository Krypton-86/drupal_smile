<?php

namespace Drupal\pets_owners_storage\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Class to store data from pets_owners_form.
 */
class PetsOwnersStorage {

  /**
   * Implements access to all pets owners.
   */
  public function access() {
    $user = \Drupal::currentUser();
    if ($user->id() == 1 && $user->hasPermission('access content')) {
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden();
    }
  }

  /**
   * Implements database info output.
   */
  public function getInfo($start = 0, $length = 10) {
    $result  = \Drupal::database()
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
      ->range($start, $length)
      ->orderBy('id', 'DESC')
      ->execute()
      ->fetchAll();
    $r       = 0;
    $h       = 0;
    $v       = 0;
    $rows    = [];
    $headers = [];
    foreach ($result as $array) {
      foreach ($array as $key => $value) {
        if ($h < 10) {
          $headers[$h] = $key;
          $h++;
        }
        elseif ($h == 10) {
          $headers[$h] = "Action A";
          $h++;
          $headers[$h] = "Action B";
        }
        $rows[$r][$v] = $value;
        $v++;
      }
      $rows[$r][$v] = Link::fromTextAndUrl('Delete', Url::fromUserInput("/pets_owners/confirm/" . $rows[$r][0] . "/delete"));
      $rows[$r][$v + 1] = Link::fromTextAndUrl('Edit', Url::fromUserInput("/pets_owners/edit/" . $rows[$r][0]));
      $v = 0;
      $r++;
    }
    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => '',
    ];
    return $content;
  }

  /**
   * Implements deleting info from database.
   */
  public function deleteInfo($id) {
    $result = \Drupal::database()
      ->delete('pets_owners_storage')
      ->condition('id', $id)
      ->execute();
  }

}
