<?php

namespace Drupal\ex81\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class HelloForm extends FormBase {

  public function getFormId() {
    return 'ex81_hello_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Please enter the title and accept the terms of use of the site.'),
    ];

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#description' => $this->t('Enter the title. Note that the title must be at least 10 characters in length.'),
      '#required' => TRUE,
    ];

    $form['accept'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('I accept the terms of use of the site.'),
      '#description' => $this->t('Please read and accept the terms of use.'),
    ];

    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form. This is not required, but is convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * Validate the title and the checkbox of the form
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $title = $form_state->getValue('title');
    $accept = $form_state->getValue('accept');

    if (strlen($title) < 10) {
      // Set an error for the form element with a key of "title".
      $form_state->setErrorByName('title', $this->t('The title must be at least 10 character long.'));
    }

    if (empty($accept)) {
      // Set an error for the form element with a key of "accept".
      $form_state->setErrorByName('accept', $this->t('You must accept the terms of use to continue.'));
    }
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Display the results.

    // Call the Static Service Container wrapper
    // We should inject the messenger service, but its beyond the scope of this example.
    $messenger = \Drupal::messenger();
    $messenger->addMessage('Title: '.$form_state->getValue('title'));
    $messenger->addMessage('Accept: '.$form_state->getValue('accept'));

    // Redirect to home
    $form_state->setRedirect('<front>');
  }

}
