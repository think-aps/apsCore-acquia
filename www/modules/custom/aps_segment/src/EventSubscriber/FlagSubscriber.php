<?php

/**
* @file
* Contains \Drupal\MODULE_NAME\EventSubscriber\FlagSubscriber.
*/

namespace Drupal\aps_segment\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\flag\Event\FlagEvents;
use Drupal\flag\Event\FlaggingEvent;
use Drupal\flag\Event\UnflaggingEvent;

class FlagSubscriber implements EventSubscriberInterface {

  	public function onFlag(FlaggingEvent $event) {
		$flagging = $event->getFlagging();
    	$flag_entity = $flagging->getFlaggable();

		// Check that the flagging entity is an Event Node
		if ($flag_entity instanceof \Drupal\node\NodeInterface && $flagging->getFlagId() == "register_interest") {
			if ($flag_entity->getType() == "event") {
				$segment_data = array(
					'type' => 'track',
					'event' => "Register Interest",
					'userId' => (int) $flagging->getOwnerId(),
					'traits' => array(
						'nodeId' => (int) $flag_entity->id(),
					),
				);
	
				process_segment_api_call('track', $segment_data);
			}
		}
  	}

  	public function onUnflag(UnflaggingEvent $event) {
    	$flagging = $event->getFlaggings();
    	$flagging = reset($flagging);
    	$flag_entity = $flagging->getFlaggable();

		// Check that the flagging entity is an Event Node
		if ($flag_entity instanceof \Drupal\node\NodeInterface && $flagging->getFlagId() == "register_interest") {
			if ($flag_entity->getType() == "event") {
				$segment_data = array(
					'type' => 'track',
					'event' => "Unregister Interest",
					'userId' => (int) $flagging->getOwnerId(),
					'traits' => array(
						'nodeId' => (int) $flag_entity->id(),
					),
				);
	
				process_segment_api_call('track', $segment_data);
			}
		}
  	}

  	public static function getSubscribedEvents() {
    	$events = [];
    	$events[FlagEvents::ENTITY_FLAGGED][] = ['onFlag'];
    	$events[FlagEvents::ENTITY_UNFLAGGED][] = ['onUnflag'];
    	return $events;
  	}
}
