<?php

namespace Drupal\entity_content_export\Plugin\ExportType;

use Drupal\Core\Annotation\Translation;
use Drupal\entity_content_export\Annotation\EntityContentExportType;
use Drupal\entity_content_export\EntityContentExportTypeBase;

/**
 * Define CSV entity content export type.
 *
 * @EntityContentExportType(
 *   id = "csv",
 *   label = @Translation("CSV"),
 *   format = "csv"
 * )
 */
class CsvEntityContentExportType extends EntityContentExportTypeBase {

  /**
   * Write CSV headers.
   *
   * @param $handle
   *   The stream handle resource.
   * @param array $data
   *   An array of data on which to extract the headers.
   */
  protected function writeCsvHeaders($handle, array $data) {
    if ($this->newFile) {
      fputcsv($handle, array_keys($data));
    }
  }

  /**
   * {@inheritDoc}
   */
  protected function writeData($handle, array $data, $is_last = FALSE) {
    $this->writeCsvHeaders($handle, $data);
    return fputcsv($handle, array_values($data));
  }
}
