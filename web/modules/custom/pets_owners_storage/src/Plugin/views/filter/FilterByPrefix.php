<?php

namespace Drupal\pets_owners_storage\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\ViewExecutable;

/**
 * Simple filter to handle filtering by gender.
 *
 * @ViewsFilter("filter_pets_owners_prefix")
 */
class FilterByPrefix extends FilterPluginBase {

  /**
   * {@inheritDoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    $form['value'] = [
      '#type' => 'select',
      '#title' => $this->t('Sex: '),
      '#options' => [
        'mr' => $this->t('male'),
        'ms|mrs' => $this->t('female'),
        '_no' => $this->t('unknown'),
      ],
      '#empty_option' => t('- Any -'),
      '#empty_value' => 'All',
      '#default_value' => $this->value,
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);
    $this->operator = "IN";
  }

  /**
   * {@inheritDoc}
   */
  public function query() {
    $this->value = explode("|", $this->value[0]);
    parent::query();
  }

}
