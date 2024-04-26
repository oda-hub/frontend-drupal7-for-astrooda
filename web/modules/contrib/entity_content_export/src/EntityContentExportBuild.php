<?php

namespace Drupal\entity_content_export;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\Entity\BaseFieldOverride;
use Drupal\Core\Render\Element;
use Drupal\Core\Render\RendererInterface;

/**
 * Define the entity content export build service.
 */
class EntityContentExportBuild {

  /**
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Entity content export build constructor.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(
    RendererInterface $renderer,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    $this->renderer = $renderer;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Build the structured entity representation.
   *
   * @param $entity_id
   *   The entity identifier.
   * @param $entity_type
   *   The entity type.
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   *   The entity display instance.
   *
   * @return array
   *   An structured array of the exported data.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function buildEntityStructure(
    $entity_id,
    $entity_type,
    EntityViewDisplayInterface $display
  ) {
    $storage = $this
      ->entityTypeManager
      ->getStorage($entity_type);

    $display_settings = $display->getThirdPartySettings(
      'entity_content_export'
    );
    $entity = $storage->load($entity_id);

    if (!$entity instanceof ContentEntityInterface) {
      return [];
    }
    $structure = [];

    foreach ($entity->getFieldDefinitions() as $field_name => $definition) {
      $is_base_field = $definition instanceof BaseFieldDefinition
        || $definition instanceof BaseFieldOverride;

      // Filter out base fields if they're not defined in the display
      // entity content export settings.
      if ($is_base_field
        && !isset($display_settings['base_fields'][$field_name])) {
        continue;
      }
      $component = $display->getComponent($field_name);

      if ($component === NULL) {
        continue;
      }
      $elements = $entity
        ->{$field_name}
        ->view($component);

      $components_settings = isset($display_settings['components'][$field_name])
        ? $display_settings['components'][$field_name]
        : [];

      if (isset($components_settings['render'])
        && $components_settings['render'] === 'value') {
        $elements = array_intersect_key(
          $elements, array_flip(Element::children($elements))
        );
      }
      $name = isset($components_settings['name'])
        ? $components_settings['name']
        : $field_name;

      $structure[$name] = (string) htmlspecialchars_decode($this->renderer->render($elements));
    }

    return $structure;
  }
}
