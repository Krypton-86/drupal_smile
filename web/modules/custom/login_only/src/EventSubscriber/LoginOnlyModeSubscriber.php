<?php

namespace Drupal\login_only\EventSubscriber;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\BareHtmlPageRendererInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\login_only\LoginOnlyModeInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * LoginOnly mode subscriber for controller requests.
 */
class LoginOnlyModeSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * The LoginOnly mode.
   *
   * @var \Drupal\login_only\LoginOnlyModeInterface
   */
  protected $loginOnlyMode;

  /**
   * The current account.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * The url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * The bare HTML page renderer.
   *
   * @var \Drupal\Core\Render\BareHtmlPageRendererInterface
   */
  protected $bareHtmlPageRenderer;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new LoginOnlyModeSubscriber.
   *
   * @param \Drupal\login_only\LoginOnlyModeInterface $login_only_mode
   *   The LoginOnly mode.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The string translation.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   The url generator.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user.
   * @param \Drupal\Core\Render\BareHtmlPageRendererInterface $bare_html_page_renderer
   *   The bare HTML page renderer.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(LoginOnlyModeInterface $login_only_mode, ConfigFactoryInterface $config_factory, TranslationInterface $translation, UrlGeneratorInterface $url_generator, AccountInterface $account, BareHtmlPageRendererInterface $bare_html_page_renderer, MessengerInterface $messenger) {
    $this->loginOnlyMode = $login_only_mode;
    $this->config = $config_factory;
    $this->stringTranslation = $translation;
    $this->urlGenerator = $url_generator;
    $this->account = $account;
    $this->bareHtmlPageRenderer = $bare_html_page_renderer;
    $this->messenger = $messenger;
  }

  /**
   * Returns the site LoginOnly page if the site is offline.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The event to process.
   */
  public function onKernelRequestLoginOnly(RequestEvent $event) {
    $request = $event->getRequest();
    $route_match = RouteMatch::createFromRequest($request);
    if ($this->loginOnlyMode->applies($route_match)) {
      // Don't cache LoginOnly mode pages.
      \Drupal::service('page_cache_kill_switch')->trigger();

      if (!$this->loginOnlyMode->exempt($this->account)) {
        // Deliver the 503 page if the site is in LoginOnly mode and the
        // logged in user is not allowed to bypass it.

        // If the request format is not 'html' then show default LoginOnly
        // mode page else show a text/plain page with LoginOnly message.
        if ($request->getRequestFormat() !== 'html') {
          $response = new Response($this->getSiteLoginOnlyMessage(), 503, ['Content-Type' => 'text/plain']);
          $event->setResponse($response);
          return;
        }
        $response = $this->bareHtmlPageRenderer->renderBarePage(['#markup' => $this->getSiteLoginOnlyMessage()], $this->t('Site under LoginOnly'), 'maintenance_page');
        $response->setStatusCode(503);
        $event->setResponse($response);
      }
      else {
        // Display a message if the logged in user has access to the site in
        // LoginOnly mode. However, suppress it on the LoginOnly mode
        // settings page.
        if ($route_match->getRouteName() != 'login_only_mode.route') {
          if ($this->account->hasPermission('administer site configuration')) {
            $this->messenger->addMessage($this->t('Operating in LoginOnly mode. <a href=":url">Go online.</a>', [':url' => $this->urlGenerator->generate('login_only_mode.route')]), 'status', FALSE);
          }
          else {
            $this->messenger->addMessage($this->t('Operating in LoginOnly mode.'), 'status', FALSE);
          }
        }
      }
    }
  }

  /**
   * Gets the site LoginOnly message.
   *
   * @return \Drupal\Component\Render\MarkupInterface
   *   The formatted site LoginOnly message.
   */
  protected function getSiteLoginOnlyMessage() {
    return new FormattableMarkup($this->config->get('system.login_only')->get('message'), [
      '@site' => $this->config->get('system.site')->get('name'),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['onKernelRequestLoginOnly', 30];
    $events[KernelEvents::EXCEPTION][] = ['onKernelRequestLoginOnly'];
    return $events;
  }

}
