<?php

namespace Drupal\pets_owners_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class for create form 'Pets owners form'.
 */
class PetsOwnersForm extends FormBase {

  /**
   * Build form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type'   => 'item',
      '#markup' => $this->t('Form for pets owners'),
    ];

    // Name.
    $form['name'] = [
      '#type'        => 'textfield',
      '#title'       => $this->t('Name'),
      '#placeholder' => $this->t('Enter your name'),
      '#description' => $this->t(
        'Name must be at least 5 characters in length.'
      ),
      '#required'    => TRUE,
    ];

    // Gender.
    $form['gender'] = [
      '#type'       => 'radios',
      '#title'      => $this->t('Gender'),
      '#attributes' => [
        'name' => 'gender',
      ],
      '#options'    => [
        'male'    => $this->t('male'),
        'female'  => $this->t('female'),
        'unknown' => $this->t('unknown'),
      ],
    ];

    // Prefix mr, ms, mrs.
    $form['prefix'] = [
      '#type'    => 'select',
      '#title'   => $this->t('Prefix'),
      '#options' => [
        'prefix' => [
          'mr'  => $this->t('mr'),
          'mrs' => $this->t('mrs'),
          'ms'  => $this->t('ms'),
        ],
      ],
      '#states'  => [
        'invisible' => [
          ':input[name="gender"]' => ['value' => 'unknown'],
        ],
      ],
    ];

    // Form age.
    $form['age'] = [
      '#type'        => 'textfield',
      '#attributes'  => [
        ' type' => 'number',
        'min'   => '1',
        'max'   => '120',
      ],
      '#title'       => $this->t('Age'),
      '#placeholder' => $this->t('Your age'),
      '#required'    => TRUE,
    ];

    // Parent form.
    $form['parent'] = [
      '#type'  => 'details',
      // '#open' => TRUE,
      '#title' => $this->t('Parents'),
    ];

    $form['parent']['mother'] = [
      '#type'        => 'textfield',
      '#title'       => $this->t("Mother's name"),
      '#placeholder' => $this->t("Mother's name"),
    ];

    $form['parent']['father'] = [
      '#type'        => 'textfield',
      '#title'       => $this->t("Father's name"),
      '#placeholder' => $this->t("Father's name"),
    ];

    // Have you some pets?.
    $form['have_pets'] = [
      '#type'  => 'checkbox',
      '#title' => 'Have you some pets?',
    ];

    $form['pets'] = [
      '#type'   => 'textfield',
      '#title'  => $this->t('Names of your pets'),
      '#states' => [
        'invisible' => [
          ':input[name="have_pets"]' => ['checked' => FALSE],
        ],
      ],
    ];

    // Email.
    $form['email'] = [
      '#type'     => 'email',
      '#title'    => $this->t('Email'),
      '#required' => TRUE,
    ];

    // Button.
    $form['button'] = [
      '#type'  => 'submit',
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
   *
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fields = [
      'name' => $form_state->getValue('name'),
      'gender' => $form_state->getValue('gender'),
      'prefix' => $form_state->getValue('prefix'),
      'age' => $form_state->getValue('age'),
      'mother_name' => $form_state->getValue('mother'),
      'father_name' => $form_state->getValue('father'),
      'have_pets' => $form_state->getValue('have_pets'),
      'pets_name' => $form_state->getValue('pets'),
      'email' => $form_state->getValue('email'),
    ];
    try {
      \Drupal::database()
        ->insert('pets_owners_storage')
        ->fields($fields)
        ->execute();
    }
    catch (\Exception $e) {
      $this->messenger()->addMessage($this->t('Error: $e'));
    }
    $this->messenger()->addMessage($this->t('Thank you!'));
  }

  /**
   * Validate form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->validateName($form_state);
    $this->validateAge($form_state);
    $this->validateEmail($form_state);
  }

  /**
   * Validate form Name.
   */
  private function validateName(&$form_state) {
    $name        = $form_state->getValue('name');
    $namePattern = "/^[a-zA-z]*$/i";
    $valid       = preg_match($namePattern, $name);
    if ($valid == FALSE) {
      $form_state->setErrorByName(
        'name',
        $this->t('Your name is not correct!')
      );
    }
    if (strlen($name) > 100) {
      $form_state->setErrorByName(
        'name',
        $this->t('Your Name must be 100 symbols max.')
      );
    }
  }

  /**
   * Validate form Age.
   */
  private function validateAge(&$form_state) {
    $age = $form_state->getValue('age');
    if (!is_numeric($age)) {
      $form_state->setErrorByName('age', $this->t('Your age must be numeric.'));
    }
    elseif ($age < 0 || $age > 120) {
      $form_state->setErrorByName(
        'age',
        $this->t('Your age should be more than 0 and less than 120')
      );
    }
  }

  /**
   * Validate form Email.
   */
  private function validateEmail(&$form_state) {
    $email        = $form_state->getValue('email');
    $emailPattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
    $valid        = preg_match($emailPattern, $email);
    if ($valid == FALSE) {
      $form_state->setErrorByName(
        'email',
        $this->t('Your email is not correct!')
      );
    }
  }

}
