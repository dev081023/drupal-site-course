<?php

/**
 * @file
 * Contains \Drupal\bda_m\Form\AddNewsForm.
 */

namespace Drupal\bda_m\Form;

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

  public function buildForm(array $form, FormStateInterface $form_state) {
    $termStorage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');
    $ids = $termStorage->getQuery()
      ->condition('vid', 'category')
      ->execute();
    $cats = [];
    foreach ($termStorage->loadMultiple($ids) as $item) {
      $cats[$item->id()] = $item->label();
    }
    $form['#attached']['library'][] = 'bda_m/custom';

    $form['#attributes']['id'] = 'example-form';
    $form['group'] = [
      '#title' => $this->t('Group 1'),
      '#type' => 'details',
      '#open' => TRUE,
      '#access' => !($form_state->has('next_page') && $form_state->get('next_page')),
    ];

    $form['group']['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#title_display' => 'after',
      '#prefix' => $this->t('Some uniq title'),
      '#markup' => $this->t('Field at latest 10 char.long'),
      '#default_value' => (new Random())->word(10),
      '#attributes' => [
        'class' => ['first', 'second'],
        'id' => 'some-id-text',
        'data-foo' => 'bar',
      ],
      //      '#autocomplete_route_name' => 'example_route_with_form_autocomplete',
    ];

    $form['group']['body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Long text'),
      '#default_value' => (new Random())->paragraphs(),
    ];

    $form['group']['category'] = [
      '#type' => 'select',
      '#title' => $this->t('Category'),
      '#options' => $cats,
    ];

    $form['group2'] = [
      '#title' => $this->t('Group 2'),
      '#type' => 'details',
      '#open' => TRUE,
      '#access' => $form_state->has('next_page') && $form_state->get('next_page'),
    ];

    $form['group2']['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#title_display' => 'after',
      '#prefix' => $this->t('Some uniq title'),
      '#markup' => $this->t('Field at latest 10 char.long'),
      '#default_value' => (new Random())->word(10),
      '#attributes' => [
        'class' => ['first', 'second'],
        'id' => 'some-id-text',
        'data-foo' => 'bar',
      ],
    ];

    $form['group2']['body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Long text'),
      '#default_value' => (new Random())->paragraphs(),
    ];

    $form['group2']['number'] = [
      '#title' => $this->t('Number'),
      '#type' => 'number',
      '#min' => 1,
      '#max' => 9999,
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['prev'] = [
      '#type' => 'submit',
      '#submit' => ['::submitPrev'],
      '#value' => $this->t('Prev step'),
      '#access' => ($form_state->has('next_page') && $form_state->get('next_page')),
      '#ajax' => [
        'callback' => '::refresh',
        'wrapper' => 'example-form',
      ],
    ];
    $form['actions']['next'] = [
      '#type' => 'submit',
      '#submit' => ['::submitNext'],
      '#value' => $this->t('Next step'),
      '#access' => !($form_state->has('next_page') && $form_state->get('next_page')),
      '#ajax' => [
        'callback' => '::refresh',
        'wrapper' => 'example-form',
      ],
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#access' => ($form_state->has('next_page') && $form_state->get('next_page')),
    ];


    return $form;
  }

  /**
   * Ajax callback for the color dropdown.
   */
  public function refresh(array $form, FormStateInterface $form_state) {
    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    if (strlen($form_state->getValue('title')) < 10) {
      $form_state->setErrorByName('title', $this->t('The title must
      be at least 10 character long.'));
      if (strlen($form_state->getValue('body')['value']) < 10) {
        $form_state->setErrorByName('body', $this->t('A message should
         contain more than 10 characters.'));
      }
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
    $message->addMessage('News with id ' . $news->id() . ' was created
    and now waiting for publishing');

    $form_state->setRedirect('<front>');
  }

  /**
   * Ajax submit handler for next step.
   *
   * @param array $form
   *   Form build array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public function submitNext(array &$form, FormStateInterface $form_state) {
    $form_state->set('next_page', TRUE);
    $form_state->setRebuild();
  }

  /**
   * Ajax submit handler for prev step.
   *
   * @param array $form
   *   Form build array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   */
  public function submitPrev(array &$form, FormStateInterface $form_state) {
    $form_state->set('next_page', FALSE);
    $form_state->setRebuild();
  }

}
