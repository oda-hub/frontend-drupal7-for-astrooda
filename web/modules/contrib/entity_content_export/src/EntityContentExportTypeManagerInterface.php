<?php

namespace Drupal\entity_content_export;

use Drupal\Component\Plugin\PluginManagerInterface;

/**
 * Define entity content export type manager interface.
 */
interface EntityContentExportTypeManagerInterface extends PluginManagerInterface {

  /**
   * Get definition format options.
   *
   * @return array
   *   An array of definition format options.
   */
  public function getDefinitionFormatOptions();
}
