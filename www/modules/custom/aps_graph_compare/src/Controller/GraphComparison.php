<?php


  namespace Drupal\aps_graph_compare\Controller;

  class GraphComparison extends \Drupal\Core\Controller\ControllerBase {
    public function field($key, $field) {
      $keys = \Drupal::config('aps_graph_compare.settings')->get('comparison_keys');
      $build = array();
      $types = array();
      $results = $percentage = $total = array();

      $title = NULL;

      foreach ($keys[$key] as $key => $type) {
        $webform = \Drupal::entityTypeManager()->getStorage('webform')->load($type);

        if ($webform instanceof \Drupal\webform\Entity\Webform && method_exists($webform, 'getElementsDecodedAndFlattened')) {
          $fields = $webform->getElementsDecodedAndFlattened();
          $submissions = aps_graph_compare_formatted_webform_submissions($webform);

          if (!$title) {
            $title = $fields[$field]['#title'];
          }

          if (array_key_exists($field, $submissions)) {
            $types[$type] = $webform->label();
            $total[$field][$type] = 0;

            foreach ($submissions[$field] as $a => $submission) {
              if (is_array($submission)) {
                foreach ($submission as $key => $response) {
                  if (!array_key_exists($response, $results)) {
                    $results[$response] = array();
                  }

                  if (!array_key_exists($type, $results[$response])) {
                    $results[$response][$type] = 1;
                  } else {
                    $results[$response][$type]++;
                  }

                  $total[$field][$type]++;
                }
              } else {
                if (!array_key_exists($submission, $results)) {
                  $results[$submission] = array();
                }

                if (!array_key_exists($type, $results[$submission])) {
                  $results[$submission][$type] = 1;
                } else {
                  $results[$submission][$type]++;
                }

                $total[$field][$type]++;
              }
            }

            foreach ($results as $field_name => $result) {
              $percentage[$field_name][$type] = (array_key_exists($field, $total) && $total[$field][$type])? round(($result[$type] / $total[$field][$type]) * 100, 1) : 0;
            }

            foreach ($fields[$field]['#options'] as $name => $label) {
              $build['submissions'][$name] = array(
                'label' => $label,
                'count' => $results[$name],
                'percentage' => $percentage[$name],
                'correct' => NULL, // @TODO: Is there any way to mark individual values within Webform responses?
              );
            }
          }
        }
      }

      return array(
        '#theme' => 'aps_graph_compare',
        '#title' => $title,
        '#submissions' => $build['submissions'],
        '#types' => $types,
      );
    }
  }
