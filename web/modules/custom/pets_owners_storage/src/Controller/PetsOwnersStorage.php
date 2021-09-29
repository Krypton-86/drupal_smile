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
   * Implements access to all pets owners list, delete, edit actions.
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
      ->range($start, $length)
      ->orderBy('id', 'DESC')
      ->execute()
      ->fetchAll();

    // Prepare headers for table.
    $headers = [
      'Record id', 'Name', 'Gender', 'Prefix', 'Age',
      'Mother', 'Father', 'Have pets?',
      'Pets name', 'Email', 'Action A', 'Action B',
    ];

    // Prepare rows for table, $r - row number, $i - item number in row.
    $rows = [];
    $r    = 0;
    $i    = 0;
    foreach ($result as $array) {
      foreach ($array as $value) {
        $rows[$r][$i] = $value;
        $i++;
      }
      $link = [
        '#markup' => "<a class='use-ajax'
      data-dialog-options='{&quot;width&quot;:200}'
      data-dialog-type='dialog'
      data-dialog-renderer='off_canvas'
      href='/pets_owners/confirm/" . $rows[$r][0] . "/delete'>Delete</a>",
      ];
      $rows[$r][$i] = \Drupal::service('renderer')->render($link);
      $rows[$r][$i + 1] = Link::fromTextAndUrl('Edit', Url::fromUserInput("/pets_owners/edit/" . $rows[$r][0]));
      $i = 0;
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
   * Implements deleting info by id from database.
   */
  public function deleteInfo($id) {
    $result = \Drupal::database()
      ->delete('pets_owners_storage')
      ->condition('id', $id)
      ->execute();
  }

}
