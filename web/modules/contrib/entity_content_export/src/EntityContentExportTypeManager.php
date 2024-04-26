<?php

namespace Drupal\entity_content_export;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Define the entity content export type manager.
 */
class EntityContentExportTypeManager extends DefaultPluginManager implements EntityContentExportTypeManagerInterface {

  /**
   * The entity content export type manager constructor.
   *
   * @param \Traversable $namespaces
   *   The available namespaces.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend instance.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler instance.
   */
  public function __construct(
    \Traversable $namespaces,
    CacheBackendInterface $cache_backend,
    ModuleHandlerInterface $module_handler
  ) {
    parent::__construct(
      'Plugin/ExportType',
      $namespaces,
      $module_handler,
      '\Drupal\entity_content_export\EntityContentExportTypeInterface',
      '\Drupal\entity_content_export\Annotation\EntityContentExportType'
    );
    $this->alterInfo('entity_content_export_type');
    $this->setCacheBackend($cache_backend, 'entity_content_export_type');
  }

  /**
   * {@inheritDoc}
   */
  public function getDefinitionFormatOptions() {
    $options = [];

    foreach ($this->getDefinitions() as $plugin_id => $definition) {
      if (!isset($definition['format'])) {
        continue;
      }
      $options[$plugin_id] = $definition['label'];
    }
    asort($options);

    return $options;
  }
}
