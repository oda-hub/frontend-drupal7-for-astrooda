<?php

/**
 * Class UnusedImagesDifferencesBase
 */
abstract class UnusedImagesDifferencesBase extends UnusedImagesActionBase {

  /**
   * @var \stdClass[]
   */
  protected $paths = array();

  /**
   * @var \stdClass[]
   */
  protected $places = array();

  /**
   * @var string[]
   */
  protected $showsWhat = '';

  /**
   * @var string[]
   */
  protected $finderVariable = '';

  /**
   * @var string[]
   */
  protected $notRunMessage = '';

  /**
   * UnusedImagesDifferencesBase constructor.
   */
  public function __construct() {
    parent::__construct();

    if (isset($this->result->paths)) {
      $this->paths = $this->result->paths;
    }
    else {
      $this->paths = $this->getPaths();
    }
    if (isset($this->result->places)) {
      $this->places = $this->result->places;
    }
    else {
      $this->places = $this->getPlaces();
    }
  }

  /**
   * {@inheritDoc}
   */
  public function getDescription() {
    return t('Shows @show_what.', array('@show_what' => $this->showsWhat));
  }

  /**
   * {@inheritDoc}
   */
  public function getHelp() {
    return t('Computes and shows a list of @show_what.', array('@show_what' => $this->showsWhat));
  }

  /**
   * {@inheritDoc}
   */
  protected function getResultsText() {
    $text = t('Last run on @date.', array('@date' => date('r', $this->result->executed), '@show_what' => $this->showsWhat));
    $text .= ' ';
    $text .= !empty($this->result->results)
      ? t('@count @show_what were found', array('@count' => count($this->result->results), '@show_what' => $this->showsWhat))
      : t('No @show_what were found.', array('@show_what' => $this->showsWhat));
    return $text;
  }

  /**
   * {@inheritDoc}
   */
  protected function getRunDetails(array $fieldset) {
    $fieldset['paths'] = array(
      '#type' => 'item',
      '#title' => t('Searched folders'),
      '#markup' => $this->implodePaths(),
      '#weight' => 5,
    );
    $fieldset['places'] = array(
      '#type' => 'item',
      '#title' => t('Searched content'),
      '#markup' => $this->implodePlaces(),
      '#weight' => 10,
    );
    $fieldset['files_found'] = array(
      '#type' => 'item',
      '#title' => t('@show_what found', array('@show_what' => ucfirst($this->showsWhat))),
      '#markup' => !empty($this->result->results)
        ? implode("<br>\n", array_map(array($this, 'resultToString'), $this->result->results))
        : t('No @show_what were found with the above settings', array('@show_what' => $this->showsWhat)),
      '#weight' => 900,
    );

    return $fieldset;
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(array $settings) {
    $results = array();
    if ($this->haveAllFindersRun()) {
      $this->paths = $this->getPaths();
      $this->places = $this->getPlaces();
      $results = $this->getDifferences();
    }
    else {
      drupal_set_message($this->notRunMessage, 'error');
      drupal_goto('admin/config/media/unused-images/status');
    }
    return $results;
  }

  /**
   * Returns whether all selected finders have been run.
   *
   * @return bool
   *   True if all selected finders have been run, false otherwise.
   */
  protected function haveAllFindersRun() {
    $allFindersHaveRun  = TRUE;
    $findersSelected = array_filter(variable_get($this->finderVariable, array()));
    foreach ($findersSelected as $key) {
      $finder = unused_images_get_action_instance($key);
      if (!$finder->hasRun()) {
        $allFindersHaveRun = FALSE;
        break;
      }
    }
    return $allFindersHaveRun;
  }

  /**
   * Returns a list of differences between images and usages found.
   *
   * The list will only contain images on the paths that have been searched and
   * for which no usage has been found on the selected usage places
   *
   * @return string[]
   */
  abstract protected function getDifferences();

  /**
   * {@inheritDoc}
   */
  protected function createResult(array $results = NULL) {
    $result = parent::createResult($results);
    $result->paths = $this->paths;
    $result->places = $this->places;
    return $result;
  }

  /**
   * Returns the set of paths for which the image finder has been run.
   *
   * @return string[]
   *   The list of paths for which the image finder has been run.
   */
  protected function getPaths() {
    $pathsRun = array();
    $pathsSelected = array_filter(variable_get('unused_images_paths', array()));
    foreach ($pathsSelected as $key) {
      /** @var \UnusedImagesImageFinder $finder */
      $finder = unused_images_get_action_instance($key);
      if ($finder->hasRun()) {
        $path = new stdClass();
        $path->key = $key;
        $path->path = $finder->getPath();
        $pathsRun[] = $path;
      }
    }
    return $pathsRun;
  }

  /**
   * Returns a list of places that have been searched for usages of images.
   *
   * @return string[]
   *   The list of places that have been searched for usages of images, keyed
   *   by their key that is used to create a finder instance for that place.
   */
  protected function getPlaces() {
    $placesRun = array();
    $usagesSelected = array_filter(variable_get('unused_images_usages', array()));
    foreach ($usagesSelected as $key) {
      /** @var \UnusedImagesUsageFinderBase $finder */
      $finder = unused_images_get_action_instance($key);
      if ($finder->hasRun()) {
        $place = new stdClass();
        $place->key = $key;
        $place->place = $finder->getUsageType();
        $placesRun[] = $place;
      }
    }
    return $placesRun;
  }

  /**
   * Returns an imploded description of the paths.
   */
  protected function implodePaths() {
    $result = '';
    $prefix = '';
    foreach ($this->paths as $path) {
      $result .= $prefix . $path->path;
      $prefix = ', ';
    }
    return $result;
  }

  /**
   * Returns an imploded description of the places.
   */
  protected function implodePlaces() {
    $result = '';
    $prefix = '';
    foreach ($this->places as $place) {
      $result .= $prefix . $place->place;
      $prefix = ', ';
    }
    return $result;
  }

  /**
   * Returns a list of images that were found by the selected image finders.
   *
   * @return string[]
   *   An array of image paths keyed by that path.
   */
  protected function getImagesFound() {
    $images = array();
    foreach ($this->paths as $path) {
      $finder = unused_images_get_action_instance($path->key);
      $runResult = $finder->getResult();
      $images = array_merge($images, $runResult->results);
    }
    $images = array_combine($images, $images);
    return $images;
  }

  /**
   * Returns a list of usages that were found by the selected usage finders.
   *
   * @return object[]
   *   A list of usages found keyed by the image path.
   */
  protected function getUsagesFound() {
    $usages = array();
    $utilities = new UnusedImagesUtilities();
    foreach ($this->places as $place) {
      $finder = unused_images_get_action_instance($place->key);
      $runResult = $finder->getResult();
      $usages = $utilities->mergeUsageResults($usages, $runResult->results, $runResult);
    }
    return $usages;
  }

}
