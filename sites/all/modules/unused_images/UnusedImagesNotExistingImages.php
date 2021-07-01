<?php

/**
 * Class UnusedImagesNotExistingImages
 */
class UnusedImagesNotExistingImages extends UnusedImagesDifferencesBase {

  /**
   * UnusedImagesUnusedImages constructor.
   */
  public function __construct() {
    $this->showsWhat = 'references to not existing images';
    $this->finderVariable = 'unused_images_paths';
    $this->notRunMessage = t('Not all image finders have run. These must be run before the set of not existing images can be determined.');
    parent::__construct();

  }

  /**
   * {@inheritDoc}
   */
  public function getActions() {
    $result = parent::getActions();

    if ($this->hasRun()) {
      $result['delete'] = array(
        'title' => t('Delete revisions'),
        'href' => 'admin/config/media/unused-images/delete',
        'query' => array('instance' => $this->getStorageKey()),
      );
    }
    return $result;
  }

  /**
   * Returns a list of unused images.
   *
   * The list will only contain images on the paths that have been searched and
   * for which no usage has been found on the selected usage places
   *
   * @return string[]
   */
  protected function getDifferences() {
    $images = $this->getImagesFound();
    $usages = $this->getUsagesFound();
    return array_values(array_diff_key($usages, $images));
  }

  /**
   * {@inheritDoc}
   */
  public function resultToString($result) {
    return (new UnusedImagesUtilities())->resultToString($result);
  }

  /**
   * Returns a list of count of non-existing images per selected usage finder.
   *
   * @return int[]
   *   A list of count of usages found for no-existing images keyed by the usage
   *   type.
   */
  protected function getUsagesFoundStatistics() {

    $usages = array();
    $utilities = new UnusedImagesUtilities();
    foreach ($this->places as $place) {
      $finder = unused_images_get_action_instance($place->key);
      $runResult = $finder->getResult();
      $usages = $utilities->mergeUsageResults($usages, $runResult->results);
    }
    return $usages;
  }

}
