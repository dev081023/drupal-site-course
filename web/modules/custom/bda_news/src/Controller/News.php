<?php

namespace Drupal\bda_news\Controller;

use Drupal\Core\Controller\ControllerBase;

class News extends ControllerBase {

  public function latestNews(): array {
    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $nodeStorage->getQuery()
    ->condition('status', 1)
    ->condition('type', 'news')
    ->sort('nid', 'DESC')
    ->pager(1)
    ->execute();

    $entity_type = 'node';
    $view_mode = 'display';
    $builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    $node = $storage->load(reset($ids));
    return $builder->view($node, $view_mode);
  }

  public function nodesByCategoryView($id) {
    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $nodeStorage->getQuery()
    ->condition('status', '1')
    ->condition('type', 'news')
    ->condition('field_category', $id)
    ->pager(6)
    ->sort('nid', 'DESC')
    ->execute();

    $entity_type = 'node';
    $view_mode = 'teaser';
    $builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    $nodes = [];
    foreach ($storage->loadMultiple($ids) as $item) {
      $nodes[] = $item;
    }
    return $builder->viewMultiple($nodes, $view_mode);
  }
}
