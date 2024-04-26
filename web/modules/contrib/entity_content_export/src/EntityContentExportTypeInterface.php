<?php

namespace Drupal\entity_content_export;

/**
 * Define the entity content export type interface.
 */
interface EntityContentExportTypeInterface {

  /**
   * Flag if the export file is new.
   *
   * @return $this
   */
  public function newFile();

  /**
   * Set the export file name.
   *
   * @param $filename
   *   The name of the export file.
   *
   * @return $this
   */
  public function setExportFilename($filename);

  /**
   * Set the export directory.
   *
   * @param $directory
   *   The directory where the file is exported.
   *
   * @return $this
   */
  public function setExportDirectory($directory);

  /**
   * Get entity export type format type.
   *
   * @return string
   *   The export type format.
   */
  public function format();

  /**
   * Get the fully qualified file path.
   *
   * @return string
   *   The fully qualified path to the export file.
   */
  public function filePath();

  /**
   * Prepend data to the file before any exported data has been written.
   */
  public function prependContent();

  /**
   * Append data to the file after all exported data has been written.
   */
  public function appendContent();

  /**
   * Write data to the file stream.
   *
   * @param array $data
   *   An array of data that should be append.
   * @param bool $is_last
   *   A boolean determining if this is the last entry.
   */
  public function write(array $data, $is_last = FALSE);
}
