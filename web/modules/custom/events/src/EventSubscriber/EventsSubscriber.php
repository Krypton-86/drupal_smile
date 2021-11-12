<?php

namespace Drupal\events\EventSubscriber;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\events\Event\NodeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 *
 */
class EventsSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;


  /**
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $route;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    $this->account = \Drupal::currentUser();
    $this->route = \Drupal::routeMatch();
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents():array {
    return [
      NodeEvent::NODE_SAVED => ['node_saved'],
      KernelEvents::REQUEST => ['page_loaded'],
    ];
  }

  /**
   * Implements for nodeSaved Event.
   */
  public function nodeSaved(NodeEvent $event) {
    $title = $event->get_node()->label();
    $type = $event->get_node()->getType();
//    $this->messenger->addStatus($this->t("@type:@title saved!"), [
//      '@type' => $type,
//      '@title' => $title,
//    ]);
    // $event->stopPropagation();
  }

  /**
   * Implements for pageLoad Event.
   */
  public function pageLoad(RequestEvent $event) {
    if ($this->account->isAnonymous() && $this->route->getRouteName() != 'user.login') {
      $event->setResponse(new RedirectResponse('/user/login', 302));
    }
  }

}
