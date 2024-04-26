<?php

namespace Drupal\entity_content_export;

use Drupal\Core\Plugin\PluginBase;

/**
 * Define the entity content export type base.
 */
abstract class EntityContentExportTypeBase extends PluginBase implements EntityContentExportTypeInterface {

  /**
   * @var string
   */
  protected $filename;

  /**
   * @var boolean
   */
  protected $newFile = FALSE;

  /**
   * @var string
   */
  protected $directory = '/tmp';

  /**
   * @var bool
   */
  protected $dataAppended = FALSE;

  /**
   * @var bool
   */
  protected $dataPrepended = FALSE;

  /**
   * {@inheritDoc}
   */
  public function newFile() {
    $this->newFile = TRUE;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function setExportFilename($filename) {
    $this->filename = $filename;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function setExportDirectory($directory) {
    if (!file_exists($directory)) {
      throw new \InvalidArgumentException(
        "The export directory doesn't exist"
      );
    }
    $this->directory = $directory;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function format() {
    return $this->pluginDefinition['format'];
  }

  /**
   * {@inheritDoc}
   */
  public function filePath() {
    $parts = [
      $this->directory,
      $this->filename
    ];
    $path = array_filter($parts);
    $path = implode('/', $path);

    return "{$path}.{$this->format()}";
  }

  /**
   * {@inheritDoc}
   */
  public function prependContent() {
    $handle = fopen($this->filePath(), 'w');
    $status = $this->writePrependedData($handle);

    if ($status) {
      $this->dataPrepended = TRUE;
    }

    fclose($handle);
  }

  /**
   * {@inheritDoc}
   */
  public function write(array $data, $is_last = FALSE) {
    $handle = fopen($this->filePath(), $this->getWriteMode());
    $status = $this->writeData($handle, $data, $is_last);

    if ($status === FALSE) {
      throw new \RuntimeException(
        'Failed to write the data to the stream.'
      );
    }
    $this->newFile = FALSE;

    fclose($handle);
  }

  /**
   * {@inheritDoc}
   */
  public function appendContent() {
    $handle = fopen($this->filePath(), 'a');
    $status = $this->writeAppendedData($handle);

    if ($status) {
      $this->dataAppended = TRUE;
    }

    fclose($handle);
  }

  /**
   * Get the export content.
   *
   * @return string|null
   *   The export file content.
   */
  public function __toString() {
    return $this->getFileContents();
  }

  /**
   * Get the write mode.
   *
   * @return string
   *   The write mode for the stream.
   */
  protected function getWriteMode() {
    return $this->newFile && !$this->dataPrepended ? 'w' : 'a';
  }

  /**
   * Get export file contents.
   *
   * @return null|string
   *   The export file contents.
   */
  protected function getFileContents() {
    return file_get_contents($this->filePath()) ?: NULL;
  }

  /**
   * Write appended data to the end of the stream handle.
   *
   * @param $handle
   *   The stream handle resource.
   *
   * @return bool
   *   If data has been appended successfully then TRUE; otherwise FALSE.
   */
  protected function writeAppendedData($handle) {
    return FALSE;
  }

  /**
   * Write prepended data to the beginning of the stream handle.
   *
   * @param $handle
   *   The stream handle resource.
   *
   * @return bool
   *   If data has been prepended successfully return TRUE; otherwise FALSE.
   */
  protected function writePrependedData($handle) {
    return FALSE;
  }

  /**
   * Write the data to the stream handle.
   *
   * @param $handle
   *   The stream handle resource.
   * @param array $data
   *   An array of data to store.
   * @param $is_last
   *   A boolean determining if this is the last entry.
   *
   * @return boolean
   *   Return TRUE if write is successful; otherwise FALSE.
   */
  abstract protected function writeData($handle, array $data, $is_last = FALSE);
}
