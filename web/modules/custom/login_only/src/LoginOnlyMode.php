<?php

namespace Drupal\login_only;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\State\StateInterface;

/**
 * Provides the default implementation of the login only mode service.
 */
class LoginOnlyMode implements LoginOnlyModeInterface {

  /**
   * The state.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * Constructs a new login only mode service.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state.
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    if (!$this->state->get('system.login_only_mode')) {
      return FALSE;
    }

    if ($route = $route_match->getRouteObject()) {
      if ($route->getOption('_maintenance_access')) {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function exempt(AccountInterface $account) {
    return $account->hasPermission('full access to site in login only mode');
  }

}
