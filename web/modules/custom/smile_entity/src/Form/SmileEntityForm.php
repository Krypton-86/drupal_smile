<?php

namespace Drupal\smile_entity\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the smile entity entity edit forms.
 */
class SmileEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New smile entity %label has been created.', $message_arguments));
      $this->logger('smile_entity')->notice('Created new smile entity %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The smile entity %label has been updated.', $message_arguments));
      $this->logger('smile_entity')->notice('Updated new smile entity %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.smile_entity.canonical', ['smile_entity' => $entity->id()]);
  }

}
