<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;

class Example extends ControllerBase {

  public function view() {
    return [
      '#markup' => 'Hello',
    ];
  }

  public function version(Node $node) {
    return new JsonResponse($node->toArray());
  }


  public function latest() {
    $storage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $storage->getQuery()
      ->condition('status', 1)
      ->condition('type', 'news')
      ->range(0, 3)
      ->execute();
    $output = [];
    $nodes = $storage->loadMultiple($ids);
    foreach ($nodes as $node) {
      $output[] = [
        'title' => $node->label(),
        'url' => $node->toUrl('canonical')->toString(),
      ];
    }
    return new JsonResponse($output);
  }

}
