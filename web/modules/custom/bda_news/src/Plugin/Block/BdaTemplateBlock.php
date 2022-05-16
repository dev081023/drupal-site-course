<?php

namespace Drupal\bda_news\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'My Template' block.
 *
 * @Block(
 *   id = "bda_template_block",
 *   admin_label = @Translation("My BDA Template")
 * )
 */
class BdaTemplateBlock extends BlockBase {

  public function defaultConfiguration(): array {
    return ['label_display' => FALSE];
  }

  public function build(): array {
    $renderable = [
      '#theme' => 'bda_template',
      '#test_var' => 'test variable',
    ];

    return $renderable;
  }

}
