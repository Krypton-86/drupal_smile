<?php

namespace Drupal\login_only;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the interface for the login only mode service.
 */
interface LoginOnlyModeInterface {

  /**
   * Returns whether the site is in login only mode.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   *
   * @return bool
   *   TRUE if the site is in login only mode.
   */
  public function applies(RouteMatchInterface $route_match);

  /**
   * Determines whether a user has access to the site in login only mode.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The logged in user.
   *
   * @return bool
   *   TRUE if the user should be exempted from login only mode.
   */
  public function exempt(AccountInterface $account);

}
