<?php

namespace Drupal\bda_m\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Bda Template' block.
 *
 * @Block(
 *   id = "bda_template_block",
 *   admin_label = @Translation("Bda Template")
 * )
 */
class BdaTemplateBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration(): array {
    return ['label_display' => FALSE];
  }

  /**
   * @inheritDoc
   */
  public function build(): array {
    $renderable = [
      '#theme' => 'bda_template',
      '#test_var' => 'test variable',
    ];

    return $renderable;
  }

}
