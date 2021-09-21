<?php

namespace Drupal\pets_owners_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class for create form 'Pets owners form'.
 */
class PetsOwnersForm extends FormBase {

  /**
   * Method to build form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Form for pets owners'),
    ];

    // Name.
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#placeholder' => $this->t('Enter your name'),
      '#description' => $this->t('Name must be at least 5 characters in length.'),
      '#required' => TRUE,
    ];

    // Gender.
    $form['settings']['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => [
        0 => $this->t('Male'),
        1 => $this->t('Female'),
        2 => $this->t('Unknown'),
      ],
      '#default_value' => 2,
      '#required' => TRUE,
    ];

    // Prefix.
    $form['settings']['prefix'] = [
      '#type' => 'select',
      '#title' => $this->t('Prefix'),
      '#options' => [
        'prefix' => [
          'mr' => $this->t('mr'),
          'mrs' => $this->t('mrs'),
          'ms' => $this->t('ms'),
        ],
      ],
    ];

    // Form age.
    $form['age'] = [
      '#type' => 'textfield',
      '#attributes' => [' type' => 'number'],
      '#title' => $this->t('Age'),
      '#placeholder' => $this->t('Your age'),
      '#required' => TRUE,
    ];

    // Parent form.
    $form['parent'] = [
      '#type' => 'details',
    // '#open' => TRUE,
      '#title' => $this->t('Parents'),
    ];

    $form['parent']['mother'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Mother's name"),
      '#placeholder' => $this->t("Mother's name"),
    ];

    $form['parent']['father'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Father's name"),
      '#placeholder' => $this->t("Father's name"),
    ];

    // Have you some pets?.
    $form['have_pets'] = [
      '#type' => 'checkbox',
      '#title' => 'Have you some pets?',
    ];
    $form['pets'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => 'pets',
      ],
      '#states' => [
        'invisible' => [
          ':input[name="have_pets"]' => ['checked' => FALSE],
        ],
      ],
    ];
    $form['pets']['have'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Names of your pets'),
    ];

    // Email.
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#required' => TRUE,
    ];

    // Button.
    $form['button'] = [
      '#type' => 'submit',
      '#value' => 'Ok',
    ];
    return $form;
  }

  /**
   * Get form id.
   */
  public function getFormId() {
    return 'pets_owners_form';
  }

  /**
   * Display “Thank you” .
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addMessage($this->t('Thank you!'));
  }

  /**
   * Validate form Name.
   */
  public function validateName(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('name');
    $namePattern = "/^[a-zA-z]*$/i";
    $valid = preg_match($namePattern, $name);
    if ($valid == FALSE) {
      $form_state->setErrorByName('name', $this->t('Your name is not correct!'));
    }
    if (strlen($name) > 100) {
      $form_state->setErrorByName('Name', $this->t('Your Name must be 100 symbols max.'));
    }
  }

  /**
   * Validate form Age.
   */
  public function validateAge(array &$form, FormStateInterface $form_state) {
    $age = $form_state->getValue('age');
    if (!is_numeric($age)) {
      $form_state->setErrorByName('Age', $this->t('Your age must be numeric.'));
    }
    elseif ($age < 0 || $age > 120) {
      $form_state->setErrorByName('Age', $this->t('Your age should be more than 0 and less than 120'));
    }
  }

  /**
   * Validate form Email.
   */
  public function validateEmail(array &$form, FormStateInterface $form_state) {
    $email = $form_state->getValue('email');
    $emailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
    $valid = preg_match($emailPattern, $email);
    if ($valid == FALSE) {
      $form_state->setErrorByName('email', $this->t('Your email is not correct!'));
    }
  }

  /**
   * Validate form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form_var = $form;
    $form_st = $form_state;
    $this->validateName($form_var, $form_st);
    $this->validateAge($form_var, $form_st);
    $this->validateEmail($form_var, $form_st);
  }

}
