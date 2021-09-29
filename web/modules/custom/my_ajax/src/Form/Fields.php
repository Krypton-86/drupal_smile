<?php

namespace Drupal\my_ajax\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Show fields based on AJAX-enabled checkbox clicks.
 *
 * @ingroup ajax_example
 */
class Fields extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_fields';
  }

  /**
   * {@inheritdoc}
   *
   * This form has two checkboxes which the user can check fields.
   *
   * For all the requests this class gets, the buildForm() method will always be
   * called. If an AJAX request comes in, the form state will be set to the
   * state the user changed that caused the AJAX request. So if the user enabled
   * one of our checkboxes, it will be checked in $form_state.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['checkbox_1'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('checkbox #1'),
      '#ajax' => [
        'callback' => '::textfieldsCallback',
        'wrapper' => 'fields-container',
        'effect' => 'fade',
      ],
    ];
    $form['checkbox_2'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('checkbox #2'),
      '#ajax' => [
        'callback' => '::urlfieldsCallback',
        'wrapper' => 'fields-container',
        'effect' => 'fade',
      ],
    ];

    // Wrap fields in a container. This container will be replaced through
    // AJAX.
    $form['fields_container'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'fields-container',
        'style' => [
          'display:none;',
        ],
      ],
    ];

    $form['url_container'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => [
          'url_container',
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addMessage(
      $this->t('Submit handler: First name: @first_name Last name: @last_name',
        [
          '@first_name' => $form_state->getValue('first_name', 'n/a'),
          '@last_name' => $form_state->getValue('last_name', 'n/a'),
        ]
      )
    );
  }

  /**
   * Implements action for checkbox #1.
   */
  public function textfieldsCallback($form, FormStateInterface $form_state) {
    if ($form_state->getValue('checkbox_1', NULL) === 1) {
      $form['fields_container']['first_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('First Name'),
        '#required' => TRUE,
      ];
      $form['fields_container']['last_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Last Name'),
        '#required' => TRUE,
      ];
      $form['fields_container']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ];
    }
    return $form['fields_container'];
  }

  /**
   * Implements action for checkbox #2.
   */
  public function urlfieldsCallback($form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $selector = '.url_container';
    if ($form_state->getValue('checkbox_2') === 1) {
      $render = '<a href="https://google.com/">go to google.com/</a>';
    }
    else {
      $render = '';
    }
    $ajax_response->addCommand(new HtmlCommand($selector, $render));
    return $ajax_response;
  }

}
