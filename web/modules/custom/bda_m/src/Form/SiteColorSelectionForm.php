<?php

/**
 * @file
 * Contains Drupal\bda_m\Form\SiteColorSelectionForm.
 */

namespace Drupal\bda_m\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * The form allows the user to change the color of the site.
 */
class SiteColorSelectionForm extends ConfigFormBase {

  /**
   * @inheritDoc
   */
  protected function getEditableConfigNames(): array {
    return ['bda_m.color_selection'];
  }

  /**
   * @inheritDoc
   */
  public function getFormId(): string {
    return 'bda_m_color_selection';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $site_config = $this->config('bda_m.color_selection');

    $form['settings'] = [
      '#type' => 'details',
      //      '#title' => $this->t('Selection color in site'),
      '#open' => TRUE,
    ];
    $form['settings']['color'] = [
      '#type' => 'radios',
      '#title' => $this->t('Color selection'),
      '#default_value' => 'light',
      '#options' => [
        'light' => $this->t('Light'),
        'dark' => $this->t('Dark'),
      ],
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    ];

    $form['#theme'] = 'system_config_form';

    return parent::buildForm($form, $form_state);
  }

  /**
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('bda_m.color_selection')
      ->set('color', $form_state->getValue('color'))
      ->save();

    $url = Url::fromUserInput('/news/view');
    $form_state->setRedirectUrl($url);

    parent::submitForm($form, $form_state);
  }

}
