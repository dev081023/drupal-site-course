<?php


namespace Drupal\bda_m\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;

class Example extends ControllerBase {

  public function api(Request $request) {
    $responce = new AjaxResponse();
    $element = [
      '#theme' => 'container',
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => ['custom-react-list'],
      ],
    ];
    $responce->addCommand(new HtmlCommand('#ajax-wrapper', $element));
    return $responce;
  }

  public function ajaxLink() {
    return [
      [
        '#theme' => 'container',
        '#attributes' => ['id' => 'ajax-wrapper'],
      ],
      [
        '#type' => 'link',
        '#title' => $this->t('Ajax link'),
        '#url' => Url::fromRoute('example_route_api'),
        '#attributes' => ['class' => ['use-ajax']],
      ],
    ];
  }

}
