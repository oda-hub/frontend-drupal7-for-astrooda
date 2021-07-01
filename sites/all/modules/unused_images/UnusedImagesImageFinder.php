<?php
/** @noinspection HtmlUnknownTarget */

/**
 * Class UnusedImagesImageFinderBase
 */
class UnusedImagesImageFinder extends UnusedImagesFinderBase {

  /**
   * The stream notation of a scheme (e.g. 'public://'), or a real path (e.g.
   * '/users/me/www/sites/all/default/').
   *
   * @var string
   */
  private $path = '';

  /** @var string[] */
  private $excludeFolders = array();

  /**
   * UnusedImagesImageFinderBase constructor.
   *
   * @param string $path
   *   A scheme (e.g. 'public'), stream notation of a scheme (e.g. 'public://'),
   *   or a real path (e.g. '/users/me/www/sites/all/default/').
   * @param string[] $extensions
   */
  public function __construct($path, array $extensions = array()) {
    /** @var DrupalStreamWrapperInterface|bool $scheme */
    $scheme = file_stream_wrapper_get_instance_by_scheme($path);
    if (!$scheme && substr($path, -strlen('://') !== '://')) {
      $scheme = file_stream_wrapper_get_instance_by_scheme($path . '://');
    }
    if ($scheme) {
      $path = $scheme->getUri();
    }
    $this->path = $path;
    if (substr($this->path, -strlen('/')) !== '/' && substr($this->path, -strlen('\\')) !== '\\') {
      $this->path .= '/';
    }

    parent::__construct($extensions);

    if (isset($this->result->excludeFolders)) {
      $this->excludeFolders = $this->result->excludeFolders;
    }
    else {
      $this->excludeFolders = variable_get('unused_images_exclude_folders', array());
    }
  }

  /**
   * Gets the base path for this image finder.
   *
   * @return string
   *   The base path for this image finder (e.g. public://).
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Sets the (sub) folders to exclude for this image finder.
   *
   * @param string $excludeFolders
   *   The (sub) folders to exclude for this image finder. This is the value as
   *   comes from a textarea, so eol may differ depending on host OS of user.
   */
  protected function setExcludeFolders($excludeFolders) {
    $excludeFolders = str_replace(array("\r\n", "\r"), array("\n", "\n"), $excludeFolders);
    $excludeFolders = explode("\n", $excludeFolders);
    $excludeFolders = array_filter($excludeFolders);
    $this->excludeFolders = $excludeFolders;
  }

  /**
   * Returns whether the given folder is to be exclude from this image search.
   *
   * @param string $folderName
   *
   * @return bool
   *   True if the given folder is to be excluded from this image search, false
   *   otherwise.
   */
  protected function isExcludeFolder($folderName) {
    return in_array($folderName, $this->excludeFolders);
  }

  /**
   * {@inheritDoc}
   */
  public function getDescription() {
    return t('Finds images on the %path path', array('%path' => $this->getPath()));
  }

  /**
   * {@inheritDoc}
   */
  public function getHelp() {
    return t('Searches for images on the %path path', array('%path' => $this->getPath()));
  }

  /**
   * {@inheritDoc}
   */
  public function fields(array $form, array &$form_state) {
    $form = parent::fields($form, $form_state);

    $form['base_path'] = array(
      '#type' => 'textfield',
      '#title' => t('Base path'),
      '#default_value' => $this->getPath(),
      '#disabled' => TRUE,
      '#description' => t('The base path or stream wrapper where to look for images. The paths can be selected on the <a href="@url">@Settings page</a>.', array(
          '@url' => url('admin/config/media/unused-images/settings'),
          '@Settings' => t('Settings'),
        )
      ),
      '#weight' => 5,
    );

    $form['exclude_folders'] = array(
      '#type' => 'textarea',
      '#title' => t('Sub folders to exclude'),
      '#default_value' => implode("\n", $this->excludeFolders),
      '#description' => t('A list of sub folders to exclude, one per line. The styles sub folder must be excluded when searching for images as they are normally not directly referenced. Adding a sub folder of which you know that it does not contain images may just speed up the process. Sub folders that (only) contain images that are referenced but not on the Drupal part of your website should be excluded as well'),
      '#weight' => 100,
    );

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  protected function getRunDetails(array $fieldset) {
    $fieldset['base_path'] = array(
      '#type' => 'item',
      '#title' => t('Searched folder'),
      '#markup' => $this->result->path,
      '#weight' => 5,
    );
    $fieldset['extensions'] = array(
      '#type' => 'item',
      '#title' => t('Extensions searched for'),
      '#markup' => implode("<br>\n", $this->result->extensions),
      '#weight' => 20,
    );
    $fieldset['excluded_folders'] = array(
      '#type' => 'item',
      '#title' => t('Sub folders that were excluded'),
      '#markup' => !empty($this->result->excludeFolders)
        ? implode("<br>\n", $this->result->excludeFolders)
        : t('No sub folders were excluded'),
      '#weight' => 30,
    );
    $fieldset['files_found'] = array(
      '#type' => 'item',
      '#title' => t('Files found'),
      '#markup' => !empty($this->result->results)
        ? implode("<br>\n", array_map(array($this, 'resultToString'), $this->result->results))
        : t('No files were found with the above settings'),
      '#weight' => 40,
    );

    return $fieldset;
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(array $settings) {
    $this->setExcludeFolders($settings['exclude_folders']);
    $results = $this->scanFolderRecursively($this->getPath());
    return $results;
  }

  /**
   * Returns a list of images found in the given folder and its sub folders.
   *
   * @param string $path
   *   The folder to search, should end with a '/'.
   *
   * @return string[]
   *   A list of the images found.
   */
  private function scanFolderRecursively($path) {
    $result = array();
    $subResult = array();

    $files = scandir($path);
    foreach ($files as $fileName) {
      $fullPath = $path . $fileName;
      if (is_dir($fullPath)) {
        if ($fileName !== '.' && $fileName !== '..' && !$this->isExcludeFolder($fileName)) {
          $subResult = array_merge($subResult, $this->scanFolderRecursively($fullPath . '/'));
        }
      }
      else {
        if ($this->isMatch($fullPath)) {
          $result[] = $fullPath;
        }
      }
    }

    $result = array_merge($result, $subResult);
    return $result;
  }

  /**
   * {@inheritDoc}
   */
  protected function createResult(array $results = NULL) {
    $result = parent::createResult($results);
    $result->path = $this->getPath();
    if ($results !== NULL) {
      $result->excludeFolders = $this->excludeFolders;
    }
    return $result;
  }

  /**
   * {@inheritDoc}
   */
  protected function getStorageKey() {
    return 'unused_images|' . static::class . '|' . rtrim($this->getPath(), ':\/');
  }

}
