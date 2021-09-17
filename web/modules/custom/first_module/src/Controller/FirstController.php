<?php

namespace Drupal\first_module\Controller;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;

class FirstController {

  public function test() {
    return [
      '#markup' => '<p>' . ('It is my first route ever') . '</p>',
    ];
  }

  public function nodeRender($nid) {
    $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
    $element = \Drupal::entityTypeManager()
      ->getViewBuilder('node')
      ->view($node, 'default');
    return [
      '#markup' => render($element)
    ];
  }

  public function access() {
    $user = \Drupal::currentUser();
    if ($user->id() == 1 && $user->hasPermission('access content')) {
      return AccessResult::allowed();
    }
    else {
      return AccessResult::forbidden();
    }
  }

}
