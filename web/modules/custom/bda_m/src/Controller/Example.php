<?php


namespace Drupal\bda_m\Controller;


use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeTypeInterface;
use Symfony\Component\HttpFoundation\Request;

class Example extends ControllerBase {

  public function api(Request $request) {
    $responce = new AjaxResponse();
    $links = array_map(function (NodeTypeInterface $type) {
      return [
        '#type' => 'link',
        '#title' => $this->t('Node add %type', ['%type' => $type->label()]),
        '#url' => Url::fromRoute('node.add', ['node_type' => $type->id()]),
        '#attributes' => [
          'class' => ['use-ajax'],
          'data-dialog-type' => 'modal',
          'data-dialog-options' => Json::encode([
            'width' => 'wide',
          ]),
        ],
      ];
    }, NodeType::loadMultiple());
    $links[] = [
      '#type' => 'link',
      '#title' => $this->t('Custom form'),
      '#url' => Url::fromRoute('bda.add_news'),
      '#attributes' => [
        'class' => ['use-ajax'],
        'data-dialog-type' => 'modal',
        'data-dialog-options' => Json::encode([
          'width' => 'wide',
        ]),
      ],
    ];
    $element = [
      '#theme' => 'item_list',
      '#items' => $links,
      '#attributes' => ['id' => Html::getUniqueId('items-list')],
      '#attached' => [
        'library' => ['bda_m/custom'],
        'drupalSettings' => [
          'foo' => 'bar',
        ],
      ],
    ];
    $responce->addCommand(new HtmlCommand('#ajax-wrapper', $element));
    //    $responce->addCommand(new RedirectCommand('/'));
    $responce->addCommand(new MessageCommand('Test message'));
    return $responce;
  }

  public function ajaxLink() {
    return [
      [
        '#theme' => 'container',
        '#attributes' => [
          'id' => 'ajax-wrapper',
        ],
      ],
      [
        '#type' => 'link',
        '#title' => $this->t('Ajax link'),
        '#url' => Url::fromRoute('example_route_api'),
        '#attributes' => [
          'class' => ['use-ajax'],
        ],
      ],
    ];
  }

}
