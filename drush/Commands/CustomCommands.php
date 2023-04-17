<?php

  namespace Drush\Commands;
  use drush\drush;

  class CustomCommands extends DrushCommands {
    /**
     *
     * See:
     * @hook pre-command config:import
     *
     */
    public function prepareInstall() {
      // Clear cache in order to prevent errors after upgrading drupal
      drupal_flush_all_caches();

      // Sets a hardcoded site uuid right before `drush config:import`
      $static_uuid_is_set = \Drupal::state()->get('static_uuid_is_set');
      if(!$static_uuid_is_set) {
        $config_factory = \Drupal::configFactory();
        $config_factory->getEditable('system.site')->set('uuid', '2ae66e5d-30e4-4df9-af3d-d13f145151d2')->save();

        Drush::output()->writeln('Setting the correct UUID for apsCore project');
        \Drupal::state()->set('static_uuid_is_set', 1);
      }

      // Checks if the module is there and installed
      $flag_is_enabled = \Drupal::state()->get('flag_module_installed');
      if (!$flag_is_enabled) {
        $installer = \Drupal::service('module_installer');

        if ($installer->install(['flag'])) {
          Drush::output()->writeln('Flag module has been pre-installed');
        } else {
          Drush::output()->writeln('Flag module couldn\'t be pre-installed');
        }

        \Drupal::state()->set('flag_module_installed', 1);
      }
    }
  }
