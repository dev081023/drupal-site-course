<?php

/**
 * @file
 * Contains Drupal\bda_m\Form\AllNewsSettingsForm.
 */

namespace Drupal\bda_m\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * News output settings form by sorting type.
 */
class AllNewsSettingsForm extends ConfigFormBase {

  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames(): array {
    return ['bda.settings'];
  }

  /**
   * @inheritDoc
   */
  public function getFormId(): string {
    return 'bda_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $site_config = $this->config('bda.settings');

    $form['settings'] = [
      '#type' => 'details',
      '#title' => $this->t('News sorting settings'),
      '#open' => TRUE,
    ];
    $form['settings']['sorted'] = [
      '#type' => 'radios',
      '#title' => $this->t('News sorting'),
      '#default_value' => 'created',
      '#options' => [
        'created' => $this->t('Sorted by creation'),
        'changed' => $this->t('Sorted by update'),
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
      '#button_type' => 'primary',
    ];

    $form['#theme'] = 'system_config_form';

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('bda.settings')
      ->set('sorted', $form_state->getValue('sorted'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
