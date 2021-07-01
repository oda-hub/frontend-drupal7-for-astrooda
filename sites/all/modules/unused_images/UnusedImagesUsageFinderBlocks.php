<?php

/**
 * Class UnusedImagesUsageFinderBlocks
 */
class UnusedImagesUsageFinderBlocks extends UnusedImagesUsageFinderTableColumn {

  /**
   * UnusedImagesUsageFinderBlocks constructor.
   *
   * @param array $extensions
   */
  public function __construct(array $extensions) {
    $this->setUsageType('Custom blocks');
    parent::__construct($extensions);
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(array $settings) {
    $this->initSearch($settings);
    $results = $this->findUsages('block_custom', 'body', array('bid'), $this->extensions);
    return $results;
  }

  /**
   * {@inheritDoc}
   */
  protected function createReference($key) {
    $reference = new stdClass();
    $reference->entity_type = 'block';
    $reference->entity_id = $key[0];
    $reference->url = sprintf('admin/structure/block/manage/block/%d/configure', $key);
    $reference->field = 'body';
    return $reference;
  }

}
