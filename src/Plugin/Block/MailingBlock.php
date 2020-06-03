<?php

namespace Drupal\mailing_subscriptions\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 *
 *
 * @Block(
 *   id = "mailingsubscriptionblock",
 *   admin_label = @Translation("Mailing Subscription Block"),
 *   category = @Translation("Mailing Subscription Block"),
 * )
 */
class MailingBlock extends BlockBase {

    public function build(){

        $build['#theme'] = 'mailing_subscription';
        $build['form'] = \Drupal::formBuilder()->getForm('Drupal\mailing_subscriptions\Form\MailingSubscriptionForm');

        $build['#attached']['library'][] = 'core/drupal.dialog.ajax';
        $build['#attached']['library'][] = 'mailing_subscriptions/mailing';

        return $build;

    }

}
