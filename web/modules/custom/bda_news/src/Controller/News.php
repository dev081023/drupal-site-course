<?php

namespace Drupal\bda_news\Controller;

use Drupal\Core\Controller\ControllerBase;

class News extends ControllerBase {

  public function latestNews() {
    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    $storage = \Drupal::entityTypeManager()->getStorage('node');

    $ids = $nodeStorage->getQuery()
    ->condition('status', 1)
    ->condition('type', 'news')
    ->sort('nid', 'DESC')
    ->pager(1)
    ->execute();

    $entity_type = 'node';
    $view_mode = 'teaser';

    $builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    $node = $storage->load(reset($ids));
    $build = $builder->view($node, $view_mode);
    $output = render($build);

    return array(
      '#markup' => $output
    );
  }
}
