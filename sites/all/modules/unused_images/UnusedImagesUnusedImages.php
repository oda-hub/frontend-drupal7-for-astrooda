<?php

/**
 * Class UnusedImagesUnusedImages
 */
class UnusedImagesUnusedImages extends UnusedImagesDifferencesBase {
  /** @var string */
  protected $status;

  protected $outdatedResults;
  /**
   * UnusedImagesUnusedImages constructor.
   */
  public function __construct() {
    $this->showsWhat = 'unused images';
    $this->finderVariable = 'unused_images_usages';
    $this->notRunMessage = t('Not all usage finders have run. These must be run before the set of unused images can be determined.');
    $this->status = 'status';
    $this->outdatedResults = array();
    parent::__construct();
  }

  /**
   * {@inheritDoc}
   */
  public function getActions() {
    $result = parent::getActions();

    if ($this->hasRun() && count($this->result->results) > 0) {
      $result['move'] = array(
        'title' => t('Move (or delete) images'),
        'href' => 'admin/config/media/unused-images/move',
        'query' => array('instance' => $this->getStorageKey()),
      );
    }
    return $result;
  }

  /**
   * {@inheritDoc}
   */
  public function fields(array $form, array &$form_state) {
    $form = parent::fields($form, $form_state);

    if ($this->hasRun() && substr(request_path(), -strlen('/move')) === '/move') {
      $form['move'] = array(
        '#type' => 'radios',
        '#title' => t('What to do with the unused images'),
        '#default_value' => 'move',
        '#options' => array(
          'move' => t('Move images'),
          'delete' => t('Delete images')
        ),
        '#description' => t('WARNING: If you choose delete, the images will be deleted permanently! There will be no confirmation form, this is the confirmation form. If you choose move they will be moved to the folder temporary:://unused-images.'),
        '#weight' => 5,
      );

      $form['actions'] = array(
        '#type' => 'actions',
        'move' => array(
          '#type' => 'submit',
          '#value' => t('Move (or delete)'),
          '#name' => 'action-move',
        ),
        '#weight' => 999,
      );
    }

    return $form;
  }

  /**
   * Submit handler for this image finder.
   *
   * This override executes the behavior of the move button, if clicked,
   * otherwise it passes the request to its parent implementation.
   *
   * @param array[] $form
   * @param array $form_state
   */
  public function submit(array $form, array &$form_state) {
    if (isset($form_state['triggering_element']['#name']) && $form_state['triggering_element']['#name'] === 'action-move') {
      $results = $this->move($form_state['values']);
      $this->saveResult($results);

      // Clear no longer valid results: image finders.
      foreach ($this->outdatedResults as $scheme) {
        $instance = new UnusedImagesImageFinder($scheme);
        $instance->deleteResult();
        drupal_set_message(t('Results for the image finder on the @path path are outdated by this action and have been removed. You may have to run it again to continue.', array('@path' => $scheme)), 'warning');
      }

      // Redirect to status page.
      drupal_goto('admin/config/media/unused-images/status');
    }
    else {
      parent::submit($form, $form_state);
    }
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
    return array_values(array_diff_key($images, $usages));
  }

  /**
   * Moves or deletes unused images.
   *
   * @param array $settings
   *  The form settings.
   *
   * @return array
   *   The list of non moved or deleted images (these are the images to remain
   *   in the result list).
   */
  private function move(array $settings) {
    $results = array();
    $doMove = $settings['move'] !== 'delete';
    foreach ($this->result->results as $unusedImage) {
      $result = $this->moveImage($unusedImage, $doMove);
      drupal_set_message($result->message, $result->status);
      if ($result->status === 'error') {
        $this->status = 'error';
        $results[] = $unusedImage;
      }
      else {
        $scheme = file_uri_scheme($unusedImage);
        if ($scheme) {
          $this->outdatedResults[$scheme] = $scheme;
        }
      }
    }
    return $results;
  }

  /**
   * Moves or deletes an unused image
   *
   * @param string $unusedImage
   * @param bool $doMove
   *
   * @return \stdClass
   *  Object with properties status and message.
   */
  private function moveImage($unusedImage, $doMove) {
    $status = 'status';
    if (file_exists($unusedImage)) {
      if ($doMove) {
        $scheme = file_uri_scheme($unusedImage);
        if ($scheme) {
          $baseTargetDir = 'temporary://unused-images/' . $scheme . '/';
          $target = file_uri_target($unusedImage);
          $targetDir = $baseTargetDir . drupal_dirname($target);
          if (is_dir($targetDir) || drupal_mkdir($targetDir, NULL, TRUE)) {
            // Create target path.
            $destination = $baseTargetDir . $target;
            $moved = file_unmanaged_move($unusedImage, $destination, FILE_EXISTS_RENAME);
            if ($moved) {
              $message = t('Image @image has been moved to @destination', array('@image' => $unusedImage, '@destination' => $destination));
            }
            else {
              $message = t('Image @image could not be moved to @destination', array( '@image' => $unusedImage, '@destination' => $destination));
              $status = 'error';
            }
          }
          else {
            $message = t('Image @image could not be moved: failed to create directory @directory', array('@image' => $unusedImage, '@directory' => $targetDir));
            $status = 'error';
          }
        }
        else {
          $message = t('Image @image is not a correct uri', array('@image' => $unusedImage));
          $status = 'error';
        }
      }
      else {
        $deleted = drupal_unlink($unusedImage);
        if ($deleted) {
          $message = t('Image @image has been deleted', array('@image' => $unusedImage));
        }
        else {
          $message = t('Image @image could not be deleted', array('@image' => $unusedImage));
          $status = 'error';
        }
      }
    }
    else {
      $message = t('Image @image no longer exists (already moved or deleted?)', array('@image' => $unusedImage));
      $status = 'error';
    }

    $result = new stdClass();
    $result->status = $status;
    $result->message = $message;
    return $result;
  }

}
