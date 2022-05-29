<?php

namespace Drupal\beetroot_example\Controllers;

use Drupal\Core\Controller\ControllerBase;

class Example extends ControllerBase {

  public function view() {
    return [
      '#markup' => 'Hello',
    ];
  }

}
