<?php


  namespace Drupal\aps_graph_compare\Routing;

  use Drupal\Core\Routing\RouteSubscriberBase;
  use Symfony\Component\Routing\Route;
  use Symfony\Component\Routing\RouteCollection;

  class DynamicRoutesAlter extends RouteSubscriberBase {

    /**
     * @inheritDoc
     */
    protected function alterRoutes(RouteCollection $collection) {
      $keys = \Drupal::config('aps_graph_compare.settings')->get('comparison_keys');
      $valid_types = array('checkboxes', 'radios');

      foreach ($keys as $key => $comparison_key) {
        $primary = \Drupal::entityTypeManager()->getStorage('webform')->load($comparison_key['primary']);

        if ($primary instanceof \Drupal\webform\Entity\Webform && method_exists($primary, 'getElementsDecodedAndFlattened')) {
          $primary_fields = $primary->getElementsDecodedAndFlattened();

          foreach ($primary_fields as $field_name => $field) {
            if (in_array($field['#type'], $valid_types)) {
              $route = new Route("/aps-graph-comparison/{$key}/{$field_name}",
                array(
                  '_title' => $field['#title'],
                  '_controller' => '\Drupal\aps_graph_compare\Controller\GraphComparison::field',
                  'key' => $key,
                  'field' => $field_name
                ),
                array(
                  '_permission' => 'access content',
                )
              );

              // Add our route to the collection with a unique key.
              $collection->add("aps_graph_compare.{$key}.{$field_name}", $route);
            }
          }
        }
      }
    }
  }
