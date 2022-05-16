<?php


namespace Drupal\bda_news\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AllNewsSettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames(): array {
    return ['bda_news.settings'];
  }

  public function getFormId(): string {
    return 'bda_news.settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $site_config = $this->config('bda_news.settings');
    $form['settings'] = [
      '#type' => 'details',
      '#title' => $this->t('News Sorting Settings'),
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
    $this->config('bda_news.settings')
      ->set('sorted', $form_state->getValue('sorted'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
