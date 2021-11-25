<?php

namespace Drupal\smile_entity\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure forum settings for this site.
 *
 * @internal
 */
class LoginOnlyForm extends ConfigFormBase {

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
    return ['system.login_only_mode'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('system.login_only_mode');

    $form['login_only_mode'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Login only'),
      '#default_value' => $config->get('login_only_mode'),
//      '#description' => $this->t('Default display order for topics.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('system.login_only_mode')
      ->set('login_only_mode', $form_state->getValue('login_only_mode'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
