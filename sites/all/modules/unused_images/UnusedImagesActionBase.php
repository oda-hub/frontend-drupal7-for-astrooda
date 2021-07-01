<?php

/**
 * Class UnusedImagesActionBase
 */
abstract class UnusedImagesActionBase {
  /** @var object */
  protected $result;

  /**
   * UnusedImagesActionBase constructor.
   */
  public function __construct() {
    $this->result = $this->retrieveResult();
  }

  public function hasRun() {
    return $this->result !== NULL && $this->result->executed !== NULL;
  }
  /**
   * Returns a short localized description of the current finder.
   *
   * This will be displayed on the status overview form.
   *
   * @return string
   *   A string containing a description for the current finder, may contain
   *   html.
   */
  abstract public function getDescription();

  /**
   * Returns help text for the current finder.
   *
   * This help will be displayed on the form and should explain in detail what
   * this finder does, indicating situations where it might not work correctly,
   * etc.
   *
   * @return string
   *   A string containing help text for the current finder, may contain html.
   */
  public function getHelp() {
    return $this->getDescription();
  }

  /**
   * Returns a string describing the status of this finder.
   *
   * @return string
   *   A string describing the status of this finder.
   */
  public function getStatusText() {
    if ($this->hasRun()) {
      $text = $this->getResultsText();
      $class = 'status';
    }
    else {
      $text = t('Not yet run');
      $class = 'warning';
    }
    return '<div class="messages ' . $class . '">' . $text . '</div>';
  }

  /**
   * Returns the results of the last run or an "empty" result if not yet run.
   *
   * @return object
   */
  public function getResult() {
    return $this->result;
  }

  /**
   * Returns a status description of the results of an action that has been run.
   *
   * @return string
   */
  abstract protected function getResultsText();

  /**
   * Converts a result to a human readable string.
   *
   * @param string|\stdClass $result
   *
   * @return string
   */
  public function resultToString($result) {
    return (string) $result;
  }

  /**
   * Returns a list of actions for this finder.
   *
   * @return array
   *   A list of actions, each action being a keyed array with the following
   *   keys:
   *   - title: The localized title of the action.
   *   - href: The link to the action page.
   *   - query (optional): a query to append to the link.
   */
  public function getActions() {
    $result = array();

    $result['run'] = array(
      'title' => $this->hasRun() ? t('Refresh') : t('Run'),
      'href' => 'admin/config/media/unused-images/run',
      'query' => array('instance' => $this->getStorageKey()),
    );
    if ($this->hasRun()) {
      $result['delete'] = array(
        'title' => t('Delete results'),
        'href' => 'admin/config/media/unused-images/delete',
        'query' => array('instance' => $this->getStorageKey()),
      );
    }
    return $result;
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
    // Common settings and elements.
    $form_state['cache'] = TRUE;

    $help = $this->getHelp();
    if ($help) {
      $form['instance_help'] = array(
        '#type' => 'markup',
        '#markup' => '<div class="help">' . $help . '</div>',
        '#weight' => -10,
      );
    }

    $form['run_status'] = array(
      '#type' => 'item',
      '#title' => t('Status'),
      '#markup' => $this->getStatusText(),
      '#weight' => -5,
    );

    if ($this->hasRun()) {
      $form['details'] = array(
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
        '#tree' => TRUE,
        '#title' => t('Details'),
        '#weight' => 990,
      );

      $form['details']['executed'] = array(
        '#type' => 'item',
        '#title' => t('Last run'),
        '#markup' => $this->getResultsText(),
        '#weight' => -5,
      );

      $form['details'] = $this->getRunDetails($form['details']);
    }

    // Buttons.
    $form['actions'] = array(
      '#type' => 'actions',
      'submit' => array(
        '#type' => 'submit',
        '#value' => $this->hasRun() ? t('Refresh') : t('Run'),
        '#name' => 'action-submit',
      ),
      '#weight' => 999,
    );

    return $form;
  }

  /**
   * Completes the details part about the last run on the form
   *
   * @param array $fieldset
   *
   * @return array
   *   The completed fieldset with details about the last run.
   */
  abstract protected function getRunDetails(array $fieldset);

  /**
   * Submit handler for this finder.
   *
   * @param array[] $form
   * @param array $form_state
   */
  public function submit(/** @noinspection PhpUnusedParameterInspection */ array $form, array &$form_state) {
    $form_state['rebuild'] = TRUE;
    $results = $this->execute($form_state['values']);
    $this->saveResult($results);
  }
  /**
   * Batch submit handler for this finder.
   */
  public function submitBatch() {
    $fields = array();
    $temp = array();
    $fields = $this->fields($fields, $temp);
    $settings = array();
    foreach ($fields as $key => $field) {
      if (isset($field['#default_value'])) {
        $settings[$key] = $field['#default_value'];
      }
    }
    $results = $this->execute($settings);
    $this->saveResult($results);
  }

  /**
   * Performs the search for images/usages.
   *
   * @param array $settings
   *   The form settings (actually it will be $form_state as used all over in
   *   Drupal form handling, but for us it is an array with keyed settings).
   *
   * @return string[]
   *   The list of images/usages found.
   */
  abstract protected function execute(array $settings);

  /**
   * Retrieves the last stored result (from cache storage).
   *
   * @return object
   *   The result of the last run, or an empty "result" object when no result
   *   has yet been stored.
   */
  private function retrieveResult() {
    $result = NULL;

    $cache = cache_get($this->getStorageKey());
    if ($cache) {
      $cache = gzuncompress($cache->data);
      if ($cache) {
        $result = json_decode($cache);
      }
    }

    if (!$result) {
      $result = $this->createResult();
    }

    return $result;
  }

  /**
   * Saves the result of a run including its context (settings and time).
   *
   * @param array $images
   *   The, possibly empty, list of images found by this image finder.
   */
  protected function saveResult(array $images) {
    $this->result = $this->createResult($images);
    $this->storeResult();
  }

  /**
   * Creates an object containing the found images or usages and its context.
   *
   * Override to add your own properties to the result object.
   *
   * @param string[]|object[]|null $results
   *   A list of found images or usages, may be empty when no images or usages
   *   were found within the defined context. Do not pass if this method is
   *   called to create a result object indicating that this image finder has
   *   not yet been run.
   *
   * @return object
   */
  protected function createResult(array $results = NULL) {
    $result = new stdClass();
    $result->key = $this->getInstanceKey();
    if ($results === NULL) {
      $result->executed = NULL;
    }
    else {
      $result->executed = time();
      $result->results = array_values($results);
    }
    return $result;
  }

  /**
   * Stores the result of a run (in the cache).
   */
  private function storeResult() {
    $value = json_encode($this->result);
    if ($value) {
      $value = gzcompress($value, 7);
      if ($value) {
        cache_set($this->getStorageKey(), $value);
      }
    }
  }

  /**
   * Stores the result of a run (in the cache).
   */
  public function deleteResult() {
    cache_clear_all($this->getStorageKey(), 'cache');
  }

  /**
   * Returns the storage key to use for this class.
   *
   * @return string
   */
  protected function getStorageKey() {
    return 'unused_images|' . static::class;
  }

  /**
   * Returns the instance key to use for this class.
   *
   * The instance key equals the storage key.
   *
   * @return string
   */
  public function getInstanceKey() {
    return $this->getStorageKey();
  }
}
