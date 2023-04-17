<?php


  namespace Drupal\aps_campaignmonitor\Controller;

  use Drupal\Core\Controller\ControllerBase;

  class SubscriptionPage extends ControllerBase {
    public function subscribe(): array {
      return array(
        '#type' => 'markup',
        '#markup' => t('You have been successfully subscribed to the mailing list for @site_name, you will only be contacted for the purposes of system notifications, or reminders, and never for marketing.', array(
          '@site_name' => \Drupal::config('system.site')->get('name'),
        )),
      );
    }

    public function unsubscribe(): array {
      return array(
        '#type' => 'markup',
        '#markup' => t('You have been successfully unsubscribed from the mailing list for @site_name.', array(
          '@site_name' => \Drupal::config('system.site')->get('name'),
        )),
      );
    }
  }
