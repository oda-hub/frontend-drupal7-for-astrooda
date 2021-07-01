<?php

/**
 * Class UnusedImagesUsageFinderManagedFiles
 */
class UnusedImagesUsageFinderManagedFiles extends UnusedImagesUsageFinderBase {

  /**
   * UnusedImagesUsageFinderManagedFiles constructor.
   *
   * @param array $extensions
   */
  public function __construct(array $extensions) {
    $this->setUsageType('Managed files');
    parent::__construct($extensions);
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(array $settings) {
    $results = array();

    $managedFileRows = db_select('file_managed', 'fm')
      ->fields('fm', array('fid', 'uri'))
      ->execute();
    foreach ($managedFileRows as $row) {
      if ($this->isMatch($row->uri)) {
        $result = new stdClass();
        $result->image = $row->uri;
        $result->references = array($this->createReference($row->fid));
        $results[] = $result;
      }
    }

    return $results;
  }

  /**
   * {@inheritDoc}
   */
  protected function createReference($key) {
    $reference = new stdClass();
    $reference->entity_type = 'file';
    $reference->entity_id = $key;
    return $reference;
  }

}
