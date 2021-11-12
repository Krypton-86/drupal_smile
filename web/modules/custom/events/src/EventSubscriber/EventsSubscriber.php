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
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

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
    $this->messenger = \Drupal::service('messenger');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      NodeEvent::NODE_SAVED => ['nodeSave', 1],
      KernelEvents::REQUEST => ['redirectAnon'],
    ];
  }

  /**
   * Implements nodeSave Event.
   */
  public function nodeSave(NodeEvent $event) {
    $this->messenger->addMessage($this->t('@type:@title saved!', [
      '@type' => $event->getNode()->getType(),
      '@title' => $event->getNode()->label(),
    ]));
    // $event->stopPropagation();
  }

  /**
   * Implements anon Event.
   */
  public function redirectAnon(RequestEvent $event) {
    $routes = [
      'user.login',
      'user.reset.login',
      'user.reset',
      'user.reset.form',
      'user.pass',
    ];
    if ($this->account->isAnonymous() && !in_array($this->route->getRouteName(), $routes)) {
      $event->setResponse(new RedirectResponse('/user/login', 302));
    }
  }

}
