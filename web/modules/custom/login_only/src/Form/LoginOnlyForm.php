<?php

namespace Drupal\login_only\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\user\PermissionHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure forum settings for this site.
 *
 * @internal
 */
class LoginOnlyForm extends ConfigFormBase {
  /**
   * The state keyvalue collection.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The permission handler.
   *
   * @var \Drupal\user\PermissionHandlerInterface
   */
  protected $permissionHandler;

  /**
   * Constructs a new Sitelogin onlyModeForm.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state keyvalue collection to use.
   * @param \Drupal\user\PermissionHandlerInterface $permission_handler
   *   The permission handler.
   */
  public function __construct(ConfigFactoryInterface $config_factory, StateInterface $state, PermissionHandlerInterface $permission_handler) {
    parent::__construct($config_factory);
    $this->state = $state;
    $this->permissionHandler = $permission_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('state'),
      $container->get('user.permissions')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'login_only_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['system.login_only'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('system.login_only');
    $form['login_only_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Login only'),
      '#default_value' => $this->state->get('system.login_only_mode'),
      '#description' => $this->t('Put site in login only mode.'),
    ];
    $form['login_only_mode_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message to display when in login only mode'),
      '#default_value' => $config->get('message'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('system.login_only')
      ->set('message', $form_state->getValue('login_only_mode_message'))
      ->save();

    $this->state->set('system.login_only_mode', $form_state->getValue('login_only_mode'));
    parent::submitForm($form, $form_state);
  }

}
