<?php

namespace Drupal\entity_content_export\Plugin\ExportType;

use Drupal\Core\Annotation\Translation;
use Drupal\entity_content_export\Annotation\EntityContentExportType;
use Drupal\entity_content_export\EntityContentExportTypeBase;

/**
 * Define JSON entity content export type.
 *
 * @EntityContentExportType(
 *   id = "json",
 *   label = @Translation("JSON"),
 *   format = "json"
 * )
 */
class JsonEntityContentExportType extends EntityContentExportTypeBase {

  /**
   * {@inheritDoc}
   */
  protected function writePrependedData($handle) {
    return fwrite($handle, "{ \"entities\": [");
  }

  /**
   * {@inheritDoc}
   */
  protected function writeData($handle, array $data, $is_last = FALSE) {
    $json = $this->formatJsonValue($data);

    if ($json === FALSE) {
      throw new \RuntimeException(
        'Unable to format the provided data to JSON.'
      );
    }
    $json_line = $json . (!$is_last ? ',' : '');

    return fwrite($handle, $json_line . PHP_EOL);
  }

  /**
   * @param $handle
   *
   * @return bool|int
   */
  protected function writeAppendedData($handle) {
    return fwrite($handle, "\r\n]}");
  }

  /**
   * Format JSON value.
   *
   * @param array $value
   *   The value array you want to format.
   *
   * @return false|string
   *   A JSON representation of the value structure; otherwise FALSE on failure.
   */
  protected function formatJsonValue(array $value) {
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_PRETTY_PRINT);
  }
}
