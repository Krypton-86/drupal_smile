<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\pets_owners_form\Form\PetsOwnersForm;
use Drupal\pets_owners_storage\Controller\PetsOwnersStorage;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;

/**
 * Class for edit form.
 */
class PetsOwnersStorageEditForm extends FormBase {

  /**
   * Implements edit view for user id.
   *
   * Method is reading info from db and
   * building form with this data as fields default values.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $record = \Drupal::database()
      ->select('pets_owners_storage', 'st')
      ->fields('st', [
        'id',
        'name',
        'gender',
        'prefix',
        'age',
        'mother_name',
        'father_name',
        'have_pets',
        'pets_name',
        'email',
      ])
      ->condition('id', $id)
      ->execute()
      ->fetchAll();
    $form_obj = new PetsOwnersForm();
    $form = $form_obj->getFormParamArray();
    if (!empty($record['0'])) {
      $form['name']['#default_value'] = !empty($record[0]->name) ? $record[0]->name : "";
      $form['gender']['#default_value'] = !empty($record[0]->gender) ? $record[0]->gender : "";
      $form['prefix']['#default_value'] = !empty($record[0]->prefix) ? $record[0]->prefix : "";
      $form['age']['#default_value'] = !empty($record[0]->age) ? $record[0]->age : "";
      $form['parent']['#open'] = !empty($record[0]->mother_name)||!empty($record[0]->father_name);
      $form['parent']['mother']['#default_value'] = !empty($record[0]->mother_name) ? $record[0]->mother_name : "";
      $form['parent']['father']['#default_value'] = !empty($record[0]->father_name) ? $record[0]->father_name : "";
      $form['have_pets']['#default_value'] = !empty($record[0]->have_pets) ? $record[0]->have_pets : "";
      $form['pets']['#default_value'] = !empty($record[0]->pets_name) ? $record[0]->pets_name : "";
      $form['email']['#default_value'] = !empty($record[0]->email) ? $record[0]->email : "";
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Change'),
        '#submit' => ['::update'],
      ];
      $form['actions']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('delete'),
        '#submit' => ['::delete'],
      ];
      // Pass value $id to submit form.
      $form_state->set('id', $id);
    }
    return $form;
  }

  /**
   * Get form id.
   */
  public function getFormId() {
    return 'pets_owners_edit_form';
  }

  /**
   * Implements form submit.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $submit = new PetsOwnersForm();
    $submit->submitForm($form, $form_state);
  }

  /**
   * Implement update method.
   */
  public function update(array &$form, FormStateInterface $form_state) {
    $fields = [
      'name'        => $form_state->getValue('name'),
      'gender'      => $form_state->getValue('gender'),
      'prefix'      => $form_state->getValue('prefix'),
      'age'         => $form_state->getValue('age'),
      'mother_name' => $form_state->getValue('mother'),
      'father_name' => $form_state->getValue('father'),
      'have_pets'   => $form_state->getValue('have_pets'),
      'pets_name'   => $form_state->getValue('pets'),
      'email'       => $form_state->getValue('email'),
    ];
    try {
      \Drupal::database()
        ->update('pets_owners_storage')
        ->fields($fields)
        ->condition('id', $form_state->get('id'))
        ->execute();
    }
    catch (\Exception $e) {
      $this->messenger()->addMessage($this->t('Error: $e'));
    }
    $this->messenger()->addMessage($this->t('Thank you!'));
    $form_state->setRedirect('pets_owners_storage.inforender');
  }

  /**
   * Implements redirect by pressing button "delete" in edit form.
   */
  public function delete(array &$form, FormStateInterface $form_state) {
    $id = ['id' => $form_state->get('id')];
    $form_state->setRedirect('pets_owners_storage.confirmdelete', $id);
  }

  /**
   * Implements validate form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $valid = new PetsOwnersForm();
    $valid->validateForm($form, $form_state);
  }

}
