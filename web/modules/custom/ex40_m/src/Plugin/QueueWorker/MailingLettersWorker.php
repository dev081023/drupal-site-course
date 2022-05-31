<?php

namespace Drupal\ex40_m\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;

/**
 * Defines 'mailing_letters_worker' queue worker.
 *
 * @QueueWorker(
 *   id = "mailing_letters_worker",
 *   title = @Translation("Mailing Letters"),
 *   cron = {"time" = 60}
 * )
 */
class MailingLettersWorker extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($id) {
    $node = Node::load($id);
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'ex40_m';
    $key = 'create_node';
    $to = \Drupal::currentUser()->getEmail();
    $params['message'] = 'Node with ' . $node->id() . ' created';
    $params['node_title'] = $node->label();
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $send = TRUE;

    $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  }

}
