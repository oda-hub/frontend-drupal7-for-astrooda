<?php

namespace Drupal\entity_content_export\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Define entity export type annotation.
 *
 * @Annotation
 */
class EntityContentExportType extends Plugin {

  /**
   * The plugin identifier.
   *
   * @var string
   */
  public $id;

  /**
   * The plugin export format.
   *
   * @var string
   */
  public $format;

  /**
   * The plugin human readable label.
   *
   * @var string
   */
  public $label;
}
