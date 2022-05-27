<?php

/**
 * @file
 * Contains \Drupal\bad_m\Plugin\Block
 */

namespace Drupal\bda_m\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'selection_color_form_block' block.
 *
 * @Block(
 *   id = "selection_color_form_block",
 *   admin_label = @Translation("Block selection color form")
 * )Drupal\bda_m\Form\SiteColorSelectionForm
 */
class SelectionColorFormBlock extends BlockBase {

  /**
   * @inheritDoc
   */
  public function build(): array {
    $form = \Drupal::formBuilder()->getForm('\Drupal\bda_m\Form\SiteColorSelectionForm');
    return $form;
  }

}
