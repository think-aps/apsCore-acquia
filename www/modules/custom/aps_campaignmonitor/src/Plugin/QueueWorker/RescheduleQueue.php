<?php

  namespace Drupal\aps_campaignmonitor\Plugin\QueueWorker;

  use Drupal\Core\Entity\EntityTypeManagerInterface;
  use Drupal\Core\Logger\LoggerChannelFactoryInterface;
  use Symfony\Component\DependencyInjection\ContainerInterface;
  use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
  use Drupal\Core\Queue\QueueWorkerBase;
  use Drupal\Core\Queue\SuspendQueueException;

  /**
   * Custom Queue Worker.
   *
   * @QueueWorker(
   *   id = "reschedule_queue",
   *   title = @Translation("Reschedule Queue"),
   *   cron = {"time" = 60}
   * )
   */
  final class RescheduleQueue extends QueueWorkerBase implements ContainerFactoryPluginInterface {

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * The database connection.
     *
     * @var \Drupal\Core\Database\Connection
     */
    protected $database;

    /**
     * Main constructor.
     *
     * @param array $configuration
     *   Configuration array.
     * @param mixed $plugin_id
     *   The plugin id.
     * @param mixed $plugin_definition
     *   The plugin definition.
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
     *   The entity type manager.
     * @param \Drupal\Core\Database\Connection $database
     *   The connection to the database.
     */
    public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager, LoggerChannelFactoryInterface $loggerChannelFactory) {
      parent::__construct($configuration, $plugin_id, $plugin_definition);
      $this->entityTypeManager = $entityTypeManager;
      $this->loggerChannelFactory = $loggerChannelFactory;
    }

    /**
     * Used to grab functionality from the container.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *   The container.
     * @param array $configuration
     *   Configuration array.
     * @param mixed $plugin_id
     *   The plugin id.
     * @param mixed $plugin_definition
     *   The plugin definition.
     *
     * @return static
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
      return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('entity_type.manager'),
        $container->get('logger.factory')
      );
    }

    /**
     * Processes an item in the queue.
     *
     * @param mixed $data
     *   The queue item data.
     *
     * @throws \Exception
     */
    public function processItem($item) {
      // Setup the headers
      $data = array(
        'auth' => array(
          \Drupal::config('aps_campaignmonitor.settings')->get('api_key'),
          'x'
        ),
      );

      $body = ($item->body)? $item->body : array();

      try {
        $data['body'] = json_encode($body);

        $request = \Drupal::httpClient()->request($item->method, APS_CM_API . $item->url, $data);

        if ($request->getStatusCode() !== $item->code) {
          if ($request->getStatusCode() === 429) {
            throw new SuspendQueueException('Queue suspended for reaching limit.');
          }

          \Drupal::logger('aps_campaignmonitor')->error(t('An invalid status code was returned by the CampaignMonitor API via @function. Status code: @status_code', array(
            '@function' => __FUNCTION__,
            '@status_code' => $request->getStatusCode(),
          )));
        }
      } catch (\Exception $e) {
        \Drupal::logger('aps_campaignmonitor')->error(t('An exception was caught trying to connect to the CampaignMonitor API via @function - \'%exception\'', array(
          '@function' => debug_backtrace()[1]['function'],
          '%exception' => $e->getMessage(),
        )));
      }
    }

  }
