<?php

  /**
   * @file
   * Contains \Drupal\aps_campaignmonitor\EventSubscriber\EventSubscriber.
   */
  namespace Drupal\aps_campaignmonitor\EventSubscriber;

  use Symfony\Component\EventDispatcher\EventSubscriberInterface;

  /**
   * Controller responses to flag and unflag action links.
   *
   * The response is a set of AJAX commands to update the
   * link in the page.
   */
  class EventSubscriber implements EventSubscriberInterface {

    public function onFlag(\Drupal\flag\Event\FlaggingEvent $event) {
      $flagging = $event->getFlagging();
      $flag_entity = $flagging->getFlaggable();

      if ($flagging->getFlagId() == \Drupal::config('aps_campaignmonitor.settings')->get('subscriber_flag')) {
        // Create list
        aps_campaignmonitor_entity_create_list($flag_entity);

        // Get entity list id
        $flagged_entity_list_id = $flag_entity->get(APS_CM_FIELD_NAME)->getString();

        // Get flagging user
        $flagging_user = $flagging->getOwner();

        // Subscribe user to list
        aps_campaignmonitor_subscribe_user_to_list($flagging_user, $flagged_entity_list_id);
      }
    }

    public function onUnflag(\Drupal\flag\Event\UnflaggingEvent $event) {
      $flagging = $event->getFlaggings();
      $flagging = reset($flagging);
      $flag_entity = $flagging->getFlaggable();

      if ($flagging->getFlagId() == \Drupal::config('aps_campaignmonitor.settings')->get('subscriber_flag')) {
        // Get entity list id
        $flagged_entity_list_id = $flag_entity->get(APS_CM_FIELD_NAME)->getString();

        // Get flagging user
        $flagging_user = $flagging->getOwner();

        // Delete user from list
        aps_campaignmonitor_delete_user_from_list($flagging_user, $flagged_entity_list_id);
      }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents() {
      $events = [];

      // Optionally return an empty array if we're not using the flag module, otherwise it'll throw an exception
      if (class_exists(\Drupal\flag\Event\FlagEvents::class)) {
        $events[\Drupal\flag\Event\FlagEvents::ENTITY_FLAGGED] = ['onFlag'];
        $events[\Drupal\flag\Event\FlagEvents::ENTITY_UNFLAGGED] = ['onUnflag'];
      }
      return $events;
    }
  }
