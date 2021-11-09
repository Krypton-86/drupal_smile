<?php

namespace Drupal\cron_batch\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements config form.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return 'cron_batch.resource';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = \Drupal::config('cron_batch.resource');

    $form['help'] = [
      '#markup' => $this->t('Config parameters'),
    ];

    $form['period'] = [
      '#type' => 'number',
      '#title' => $this->t('Period:'),
      '#default_value' => $config->get('period'),
    ];

    $form['items'] = [
      '#type' => 'number',
      '#title' => $this->t('Items to create:'),
      '#default_value' => $config->get('items'),
    ];

    $form['disabled'] = [
      '#type' => 'radios',
      '#title' => $this->t('Disabled:'),
      '#options' => ['y' => $this->t('Yes'), 'n' => $this->t('No')],
      '#default_value' => $config->get('disabled') == 0 ? 'n' : 'y',
    ];

    $form['unpublished_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Unpublished label:'),
      '#default_value' => $config->get('unpublished_label'),
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('period') < 180) {
      $form_state->setErrorByName('period', $this->t('Minimum period is 180!'));
    }
    if ($form_state->getValue('items') < 5 || $form_state->getValue('items') > 25) {
      $form_state->setErrorByName('items', $this->t('Must be more than 5 and less than 25!'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::configFactory()->getEditable('cron_batch.resource');
    $config->set('period', $form_state->getValue('period'))
      ->set('items', $form_state->getValue('items'))
      ->set('disabled', $form_state->getValue('disabled') == 'y' ? 1 : 0)
      ->set('unpublished_label', $form_state->getValue('unpublished_label'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
