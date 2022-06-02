<?php

/**
 * Add new custom page
 */
function bda_product_post_update_create_some_page(&$sandbox) {
  \Drupal\node\Entity\Node::create([
    'type' => 'page',
    'title' => 'Some page',
    'status' => 1,
  ])->save();
  \Drupal::messenger()->addMessage('Added some page');
}

/**
 * Set FALSE value from flag field
 */
function bda_product_post_update_set_value_flag(&$sandbox) {
  $storage = \Drupal::entityTypeManager()->getStorage('node');
  $ids = $storage->getQuery()
    ->condition('type', 'product')
    ->execute();

  $nodes = \Drupal\node\Entity\Node::loadMultiple($ids);
  foreach ($nodes as $node) {
    $node->set('flag', FALSE);
    $node->save();
  }
}

