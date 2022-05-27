<?php

/**
 * @file
 * Contains \Drupal\bda_m\Controller\News.
 */

namespace Drupal\bda_m\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\simple_sitemap\Manager\EntityManager;

/**
 * A class that describes methods for working with the News content type.
 */
class News extends ControllerBase {

  public function view(): array {
    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $nodeStorage->getQuery()
      ->condition('type', 'news')
      ->execute();
    $nodes = Node::loadMultiple($ids);
    $output = [];
    $entity_type_id = 'taxonomy_term';
    $bundle = 'category';
    foreach ($nodes as $node) {
      $links = [];
      if ($node->hasField('field_category')) {
        /** @var \Drupal\node\NodeInterface[] $related */
        $related = $node->get('field_category')->referencedEntities();
        foreach ($related as $item) {
          $links[] = [
            '#theme' => 'beetroot_example_news_link',
            '#url' => $item->toUrl('canonical')->toString(),
            '#title' => $item->label(),
            $item->getFieldDefinition('field_favorites'),
          ];
        }
      }
      $output[] = [
        '#theme' => 'bda_example_news',
        '#title' => $node->label(),
        '#content' => $node->get('body')->view(['label' => 'hidden']),
        '#category' => $node->get('field_category')->target_id,
        '#attached' => [
          'library' => [
            'bda_m/custom',
          ],
        ],
      ];
    }
    return $output;
  }

  /**
   * Displays the latest published news.
   */
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

  /**
   * Displays a list of news selected by news category id.
   *
   * @param $id
   *  News category id.
   *
   * @return mixed
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function nodesByCategoryView($id) {
    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $nodeStorage->getQuery()
      ->condition('status', 1)
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

  /**
   * Sorts the list of news selected by the sorting method (created/changed)
   * passed from the form in the settings.
   *
   * @return mixed
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getAllNewsBySorted() {
    $config = \Drupal::config('bda.settings');
    $nodeStorage = \Drupal::entityTypeManager()->getStorage('node');
    $ids = $nodeStorage->getQuery()
      ->condition('status', 1)
      ->condition('type', 'news')
      ->pager(6)
      ->sort($config->get('sorted'), 'DESC')
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
