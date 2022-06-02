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
    $storage = \Drupal::entityTypeManager()->getStorage('user');
    /** @var \Drupal\user\UserInterface $users */
    $users = $storage->loadMultiple($storage->getQuery()
      ->condition('role', 'authenticated')
      ->execute());
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'ex40_m';
    $key = 'create_node';
    $send = TRUE;

    foreach ($users as $user) {
      /** @var $mailManager \Drupal\Core\Mail\MailManagerInterface */
      $mailManager->mail($module, $key,
        $user->getEmail(), 'en',
        ['message' => 'Node with ' . $node->id() . ' created', 'node_title' => $node->label()],
        NULL, $send);
    }
  }

}
