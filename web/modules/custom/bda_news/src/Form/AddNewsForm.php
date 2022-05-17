<?php

/**
 * @file
 * Contains Drupal\bda_news\Form\AddNewsForm.
 */
namespace Drupal\bda_news\Form;

use Drupal\Component\Utility\Random;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Form for adding news.
 */
class AddNewsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'bda_add_news_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
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
      '#default_value' => (new Random())->word(10),
    ];

    $form['body'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Content'),
      '#format' => 'basic_html',
      '#default_value' => (new Random())->paragraphs(),
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

    if (strlen($form_state->getValue('title')) < 10) {
      $form_state->setErrorByName('title', $this->t('The title must be at least 10 character long.'));
    }

    if (strlen($form_state->getValue('body')['value']) <= 3) {
      $form_state->setErrorByName('body', $this->t('A message should contain more than 10 characters.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $body = $form_state->getValue('body')['value'];
    $body = check_markup($body, 'basic_html');
    $news = Node::create([
      'type' => 'news',
      'title' => $form_state->getValue('title'),
      'body' => [
        'value' => $body,
      ],
      'field_category' => $form_state->getValue('category'),
      'uid' => \Drupal::currentUser()->id(),
    ]);
    $news->setUnpublished();
    $news->save();

    $message = \Drupal::messenger();
    $message->addMessage('News with id ' . $news->id() . ' was created and now waiting for publishing');

    $form_state->setRedirect('<front>');
  }

}
