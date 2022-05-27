<?php

/**
 * @file
 * Contains \Drupal\bad_m\Plugin\Block
 */

namespace Drupal\bda_m\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'registration_for_events' block.
 *
 * @Block(
 *   id = "registration_for_events_form_block",
 *   admin_label = @Translation("Registration block for webinars")
 * )
 */
class RegistrationForEventsBlock extends BlockBase {

  /**
   * @inheritDoc
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\bda_m\Form\RegistrationForEventsForm');
    return $form;
  }

}
