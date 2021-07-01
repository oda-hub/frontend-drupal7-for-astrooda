<?php

/**
 * Class UnusedImagesFinderBase
 */
abstract class UnusedImagesFinderBase extends UnusedImagesActionBase {
  /** @var string[] */
  protected $extensions = array();

  /**
   * UnusedImagesFinderBase constructor.
   *
   * @param string[] $extensions
   */
  public function __construct(array $extensions) {
    $this->extensions = $extensions;
    parent::__construct();
  }

  /**
   * {@inheritDoc}
   */
  protected function getResultsText() {
    $text = t('Last run on @date.', array('@date' => date('r', $this->result->executed)));
    $text .= ' ';
    $what = $this instanceof UnusedImagesImageFinder ? t('images') : t('usages');
    $text .= !empty($this->result->results)
      ? t('@count @what were found.', array('@count' => count($this->result->results), '@what' => $what))
      : t('No @what were found.', array('@what' => $what));
    return $text;
  }

  /**
   * Get the form fields specific for this finder.
   *
   * @param array[] $form
   * @param array $form_state
   *
   * @return array[]
   *   The completed form
   */
  public function fields(array $form, array &$form_state) {
    $form = parent::fields($form, $form_state);

    /** @noinspection HtmlUnknownTarget */
    $form['extensions'] = array(
      '#type' => 'textfield',
      '#title' => t('File extensions'),
      '#default_value' => $this->extensions,
      '#disabled' => TRUE,
      '#description' => t('A comma separated list of file extensions to restrict the search to. This setting can be edited on the <a href="@url">@Settings page</a>.', array(
          '@url' => url('admin/config/media/unused-images/settings'),
          '@Settings' => t('Settings'),
        )
      ),
      '#weight' => 10,
    );

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  protected function createResult(array $results = NULL) {
    $result = parent::createResult($results);
    $result->extensions = $this->extensions;
    return $result;
  }

  /**
   * Returns whether the given file name matches this image search.
   *
   *  A file name matches iff:
   *  - The file does have an extension and that matches the list of extensions
   *    (case insensitive).
   *  - The name part is not empty: this will exclude files like .jpg
   *
   * @param string $fileName
   *
   * @return bool
   *   True if the given file name matches the settings for this image search,
   *   false otherwise.
   */
  protected function isMatch($fileName) {
    $fileInfo = pathinfo($fileName);
    return !empty($fileInfo['filename']) && !empty($fileInfo['extension']) && in_array(strtolower($fileInfo['extension']), $this->extensions);
  }
}
