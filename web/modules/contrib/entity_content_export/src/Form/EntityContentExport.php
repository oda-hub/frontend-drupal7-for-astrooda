<?php

namespace Drupal\entity_content_export\Form;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\entity_content_export\BatchExport;
use Drupal\entity_content_export\EntityContentExportTypeInterface;
use Drupal\entity_content_export\EntityContentExportTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Define entity content export form.
 */
class EntityContentExport extends FormBase {

  /**
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\entity_content_export\EntityContentExportTypeManagerInterface
   */
  protected $entityExportTypeManager;

  /**
   * Define entity content export form constructor.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   * @param \Drupal\entity_content_export\EntityContentExportTypeManagerInterface $entity_export_type_manager
   */
  public function __construct(
    RendererInterface $renderer,
    ConfigFactoryInterface $config_factory,
    EntityTypeManagerInterface $entity_type_manager,
    EntityContentExportTypeManagerInterface $entity_export_type_manager
  ) {
    $this->renderer = $renderer;
    $this->setConfigFactory($config_factory);
    $this->entityTypeManager = $entity_type_manager;
    $this->entityExportTypeManager = $entity_export_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('renderer'),
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('plugin.manager.entity_content_export_type')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'entity_content_export';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $options = $this->getExportableEntityOptions();

    if (empty($options)) {
      $this->messenger()->addError(
        $this->t('No entity type bundles have been configured to be exported.')
      );
    }
    $form['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Content Type'),
      '#required' => TRUE,
      '#options' => $options,
    ];
    $form['format'] = [
      '#type' => 'radios',
      '#title' => $this->t('Exported Format'),
      '#description' => $this->t('Select the exported format.'),
      '#options' => $this->entityExportTypeManager->getDefinitionFormatOptions(),
      '#required' => TRUE,
    ];
    $form['options'] = [
      '#type' => 'details',
      '#title' => $this->t('Export Options'),
      '#open' => FALSE,
      '#tree' => TRUE,
    ];
    $form['options']['exclude_non_published'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Exclude non-published content')
    ];
    $form['actions']['#type'] = 'actions';

    $form['actions']['export'] = [
      '#type' => 'submit',
      '#value' => $this->t('Export')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    if (!isset($values['type']) || !isset($values['format'])) {
      return;
    }
    $export_options = $values['options'] ?? [];

    /** @var \Drupal\entity_content_export\EntityContentExportTypeInterface $export_type */
    $export_type = $this
      ->entityExportTypeManager
      ->createInstance($values['format']);

    if (!$export_type instanceof EntityContentExportTypeInterface) {
      return;
    }
    list($entity_type, $bundle) = explode(':', $values['type']);

    $export_type
      ->setExportDirectory(\Drupal::service('file_system')->getTempDirectory())
      ->setExportFilename("entity-content-export-{$entity_type}");

    $display = $this->getEntityViewDisplay($entity_type, $bundle);

    $batch = [
      'title' => $this->t('Exporting @entity_type of type @bundle', [
        '@bundle' => $bundle,
        '@entity_type' => $entity_type,
      ]),
      'operations' => [
        [
          '\Drupal\entity_content_export\EntityContentExportBatch::export',
          [$entity_type, $bundle, $display, $export_type, $export_options]
        ],
      ],
      'finished' => '\Drupal\entity_content_export\EntityContentExportBatch::finished',
    ];
    batch_set($batch);
  }

  /**
   * Get the temporary directory.
   *
   * @return array|mixed
   */
  protected function tempDirectory() {
    $temporaryUri = \Drupal::config('entity_content_export.settings')
      ->get('temporary_uri');

    return $temporaryUri ?? \Drupal::service('file_system')->getTempDirectory();
  }

  /**
   * Get exportable entity options.
   *
   * @return array
   *   An array of exportable entity options.
   */
  protected function getExportableEntityOptions() {
    $options = [];

    foreach ($this->getConfiguration()->get('entity_type_bundles') as $entity_type_bundle) {
      list($entity_type, $bundle) = explode(':', $entity_type_bundle);

      $options[$entity_type_bundle] = $this->t('@entity_type: @bundle', [
        '@bundle' => $this->capitalizeWords($bundle),
        '@entity_type' => $this->capitalizeWords($entity_type)
      ]);
    }

    return $options;
  }

  /**
   * Get entity view display.
   *
   * @param $entity_type
   *   The entity type.
   * @param $bundle
   *   The entity bundle type.
   * @param string $default_mode
   *   The entity default view mode.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The entity view display instance.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function getEntityViewDisplay($entity_type, $bundle, $default_mode = 'default') {
    $view_mode = $this->getEntityViewDisplayModeFromConfig(
      $entity_type, $bundle, $default_mode
    );
    $display_ids = $this->entityViewDisplayQuery()
      ->condition('mode', $view_mode)
      ->condition('bundle', $bundle)
      ->condition('targetEntityType', $entity_type)
      ->execute();

    if (empty($display_ids)) {
      return $this->entityViewDisplayStorage()
        ->create([
          'mode' => $view_mode,
          'bundle' => $bundle,
          'targetEntityType' => $entity_type
        ]);
    }
    $display_id = reset($display_ids);

    return $this->loadEntityViewDisplay($display_id);
  }

  /**
   * Entity view display query.
   *
   * @return \Drupal\Core\Entity\Query\QueryInterface
   *   The entity query instance.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function entityViewDisplayQuery() {
    return $this->entityViewDisplayStorage()
      ->getQuery();
  }

  /**
   * Get entity view display storage.
   *
   * @return \Drupal\Core\Entity\EntityStorageInterface
   *   The entity view display storage instance.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function entityViewDisplayStorage() {
    return $this->entityTypeManager
      ->getStorage('entity_view_display');
  }

  /**
   * Load entity view display.
   *
   * @param $display_id
   *   The entity display identifier.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The entity view display
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  protected function loadEntityViewDisplay($display_id) {
    return $this->entityViewDisplayStorage()->load($display_id);
  }

  /**
   * Get entity view display mode from configuration.
   *
   * @param $entity_type
   *   The entity type.
   * @param $bundle
   *   The entity bundle type.
   * @param string $default_mode
   *   The entity default mode.
   *
   * @return string
   *   The entity view display mode.
   */
  protected function getEntityViewDisplayModeFromConfig($entity_type, $bundle, $default_mode = 'default') {
    $bundle_config = $this->getConfiguration()
      ->get("entity_bundle_configuration.{$entity_type}.{$bundle}");

    return isset($bundle_config['display_mode']) && !empty($bundle_config['display_mode'])
      ? $bundle_config['display_mode']
      : $default_mode;
  }

  /**
   * Capitalize words in a string.
   *
   * @param $string
   *   The string to capitalize.
   *
   * @return string
   *   The transformed string.
   */
  protected function capitalizeWords($string) {
    return Unicode::ucwords(strtr($string, '_', ' '));
  }

  /**
   * Get entity content export settings.
   *
   * @return \Drupal\Core\Config\ImmutableConfig
   *   The configuration instance.
   */
  protected function getConfiguration() {
    return $this->configFactory->get('entity_content_export.settings');
  }
}
