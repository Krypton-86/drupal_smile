<?php

namespace Drupal\smile_entity\EventSubscriber;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Redirect Anonymous to login page.
 */
class HttpRefererSubscriber implements EventSubscriberInterface {

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * Messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * Route.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $route;

  /**
   * {@inheritdoc}
   */
  public function __construct(MessengerInterface $messenger,
                              AccountInterface $currentUser,
                              RouteMatchInterface $route) {
    $this->account = $currentUser;
    $this->route = $route;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[KernelEvents::REQUEST][] = ['checkReferer'];
    return $events;
  }

  /**
   * Check and redirect to login page.
   */
  public function checkReferer(RequestEvent $event) {
    // Array of routes to redirect on home page.
    $routes = [
      'entity.smile_entity.canonical',
    ];
    if (!strpos($event->getRequest()->headers->get('referer'), $event->getRequest()->getHost()) && in_array($this->route->getRouteName(), $routes)) {
      $event->setResponse(new RedirectResponse('/'));
    }
  }

}
