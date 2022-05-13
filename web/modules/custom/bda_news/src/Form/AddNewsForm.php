<?php

namespace Drupal\bda_news\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class AddNewsForm extends FormBase {

  public function getFormId() {
    return 'bda_add_news_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $termStorage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $ids = $termStorage->getQuery()
      ->condition('vid', 'category')
      ->execute();

    $cats = [];
    foreach ($termStorage->loadMultiple($ids) as $item) {
      $cats[$item->id()] = $item->label();
    }

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#markup' => $this->t('Field at least 10 characters long'),
      '#required' => TRUE,
    ];

    $form['content'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Content'),
//      '#markup' => $this->t('Field at least 10 characters long'),
      '#format' => 'full_html',
      '#required' => TRUE,
    ];

    $form['category'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#options' => $cats,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $title = $form_state->getValue('title');
    $content = $form_state->getValue('content');

    if (strlen($title) < 10) {
      // Set an error for the form element with a key of "title".
      $form_state->setErrorByName('title', $this->t('The title must be at least 10 character long.'));
    }

//    if (strlen($content) < 10) {
//      // Set an error for the form element with a key of "title".
//      $form_state->setErrorByName('title', $this->t('The content must be at least 10 character long.'));
//    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
//    $em = \Drupal::entityTypeManager()->getStorage('node');
    $news = Node::create([
      'type' => 'news',
      'title' => $form_state->getValue('title'),
      'body' => [
        'value' => $form_state->getValue('content'),
      ],
      'uid' => \Drupal::currentUser()->id(),
    ]);
    $news->setUnpublished();
    $news->save();

    $message = \Drupal::messenger();
    $message->addMessage('News with id ' . \Drupal::currentUser()->id() . ' was created and now waiting for publishing');

    $form_state->setRedirect('<front>');
  }

}
