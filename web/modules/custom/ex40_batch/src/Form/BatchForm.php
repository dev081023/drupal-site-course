<?php


namespace Drupal\ex40_batch\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class BatchForm extends FormBase {

  /**
   * {@inheritdoc}
   */

  public function getFormId(): string {
    return 'ex40_batch_form';
  }

  /**
   * {@inheritdoc}
   */

  public function buildForm(array $form, FormStateInterface $form_state) {
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $nodeIds = $storage->getQuery()
      ->condition('type', 'news')
      ->condition('field_archive', 0)
      ->execute();
    $nodes = $storage->loadMultiple($nodeIds);
    $output = [];
    foreach ($nodes as $node) {
      $output[] = [
        'id' => $node->id(),
        'title' => $node->label(),
      ];
    }
    $header = [
      'title' => $this->t('Node`s title'),
    ];
    $form['table'] = [
      '#type' => 'tableselect',
      '#header' => $header,
      '#options' => $output,
      '#empty' => t('No node`s found'),
    ];
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nodes = $form['table']['#options'];
    $selected = $form_state->getValue('table');
    $i = 0;
    $operations = [];
    foreach ($nodes as $node) {
      if (is_string($selected[$i])) {
        $operations[] = [
          '\Drupal\ex40_batch\Form\BatchForm::toArchive',
          [$node['id']],
        ];
      }
      $i++;
    }
    batch_set([
      'title' => $this->t('Archive selected News'),
      'operations' => $operations,
    ]);
  }

  /**
   * Node to archive checked
   */
  public static function toArchive($params) {
    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    $node = $nodeStorage->load($params);
    $node->set('field_archive', 1);
    $node->save();
  }

}
