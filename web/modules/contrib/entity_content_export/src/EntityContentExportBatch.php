<?php

namespace Drupal\entity_content_export;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Define entity content export batch.
 */
class EntityContentExportBatch {

  /**
   * Export entity data.
   *
   * @param $entity_type
   *   The entity type on which to export.
   * @param $bundle
   *   The entity bundle type.
   * @param \Drupal\Core\Entity\Display\EntityViewDisplayInterface $display
   *   The entity display service.
   * @param \Drupal\entity_content_export\EntityContentExportTypeInterface $export_type
   *   The export type service.
   * @param array $export_options
   *   An array of export options.
   * @param array $context
   *   An array of the batch context.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function export(
    $entity_type,
    $bundle,
    EntityViewDisplayInterface $display,
    EntityContentExportTypeInterface $export_type,
    array $export_options = [],
    array &$context
  ) {
    $limit = 10;

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = \Drupal::service('entity_type.manager');

    /** @var \Drupal\Core\Entity\EntityTypeInterface $entity_type_definition */
    $entity_type_definition = $entity_type_manager
      ->getDefinition($entity_type);

    $query = $entity_type_manager
      ->getStorage($entity_type)
      ->getQuery();

    if (isset($export_options['exclude_non_published'])
      && $export_options['exclude_non_published']) {

      if ($status_key = $entity_type_definition->getKey('status')) {
        $query->condition($status_key, TRUE);
      }
    }
    $query->condition($entity_type_definition->getKey('bundle'), $bundle);

    if (empty($context['sandbox'])) {
      $count_query = clone $query;
      $context['sandbox'] = [];
      $context['sandbox']['batch'] = 0;
      $context['sandbox']['iterations'] = abs(ceil($count_query->count()->execute() / $limit));
    }
    $batch = &$context['sandbox']['batch'];
    $iterations = $context['sandbox']['iterations'];

    /** @var \Drupal\entity_content_export\EntityContentExportBuild $export_builder */
    $export_builder = \Drupal::service('entity_content_export.build');

    $offset = $batch * $limit;

    if ($offset === 0) {
      $export_type->newFile();
      $export_type->prependContent();
    }
    $entities = $query->range($offset, $limit)->execute();

    $current_count = 1;
    $process_count = count($entities);

    foreach ($entities as $entity_id) {
      $export_type->write(
        $export_builder->buildEntityStructure($entity_id, $entity_type, $display),
        $process_count === $current_count && $iterations == $batch + 1
      );
      $current_count++;
    }

    $context['message'] = new TranslatableMarkup(
      'Exporting entity content (@batch/@iterations).', [
        '@batch' => $batch + 1,
        '@iterations' => $iterations
      ]
    );
    $batch++;

    if ($batch != $iterations) {
      $context['finished'] = $batch / $iterations;
    }

    if ($context['finished'] >= 1) {
      $export_type->appendContent();
      $context['results']['file'] = $export_type->filePath();
    }
  }

  /**
   * The finished callback for the entity content export.
   *
   * @param $success
   *   A boolean if the batch process was successful.
   * @param $results
   *   An array of results for the given batch process.
   * @param $operations
   *   An array of batch operations that were performed.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public static function finished($success, $results, $operations) {
    $redirect_url = Url::fromRoute('entity_content_export.download.results', [
      'results' => $results
    ])->toString();

    return new RedirectResponse($redirect_url);
  }
}
