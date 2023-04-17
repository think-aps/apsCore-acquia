<?php


	namespace Drupal\aps_graph_compare\Plugin\Block;

  /**
   * Provides the Graph Comparison grid as a block
   *
   * @Block(
   *   id = "aps_graph_comparison",
   *   admin_label = @Translation("Graph Comparison")
   * )
   *
   * Class GraphComparisonBlock
   * @package Drupal\aps_graph_compare\Plugin\Block
   */
	class GraphComparisonsBlock extends \Drupal\Core\Block\BlockBase {

		/**
		 * @inheritDoc
		 */
		public function build() {
      return \Drupal::formBuilder()->getForm('Drupal\aps_graph_compare\Form\APSWebformComparisonSettings');
		}
	}
