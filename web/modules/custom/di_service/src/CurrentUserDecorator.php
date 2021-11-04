<?php

namespace Drupal\di_service;

use Drupal\Core\Session\AccountProxy;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Decorates CurrentUser.
 */
class CurrentUserDecorator extends AccountProxy {

  protected AccountProxyInterface $originalService;

  /**
   * AccountProxy constructor.
   */
  public function __construct(AccountProxyInterface $current_user, EventDispatcherInterface $eventDispatcher) {
    $this->originalService = $current_user;
    parent::__construct($eventDispatcher);
  }

  /**
   * Override Email.
   *
   * @return string
   */
  public function getDisplayName() {

    $email = $this->getAccount()->getEmail();
    return "Email: $email";
  }

}
