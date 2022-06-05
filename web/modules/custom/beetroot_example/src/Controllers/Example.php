<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\Core\Controller\ControllerBase;
use Laminas\Diactoros\Response\JsonResponse;

class Example extends ControllerBase {

  public function view() {
    return [
      '#markup' => 'Hello',
    ];
  }


  public function version() {
    return new JsonResponse([
      'version' => \Drupal::VERSION,
      'time' => \Drupal::time()->getCurrentTime(),
    ]);
  }

}
