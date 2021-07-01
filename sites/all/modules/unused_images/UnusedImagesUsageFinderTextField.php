<?php

/** @noinspection PhpUnused
 *
 * Class UnusedImagesUsageFinderTextField searches in text fields (blob of text)
 * for usages of images.
 */
class UnusedImagesUsageFinderTextField extends UnusedImagesUsageFinderTableColumn {

  /**
   * @var string
   */
  protected $fieldName;

  /**
   * @var string
   */
  protected $fieldLabel;

  /**
   * @var bool
   */
  protected $revisions;

  /**
   * @var string[][]
   */
  protected $keyFields = array(
    FIELD_LOAD_CURRENT => array('entity_type', 'entity_id', 'language', 'delta'),
    FIELD_LOAD_REVISION => array('entity_type', 'entity_id', 'revision_id', 'language', 'delta')
  );

  /**
   * UnusedImagesUsageFinderTextField constructor.
   *
   * @param string $fieldName
   * @param array $extensions
   */
  public function __construct($fieldName, array $extensions) {
    $this->fieldName = $fieldName;
    $textFields = (new UnusedImagesUtilities())->getFieldsThatMayBeSearched();
    $this->fieldLabel = $textFields[$this->fieldName];
    $this->setUsageType('Field ' . $this->fieldLabel);
    parent::__construct($extensions);
    if (isset($this->result->revisions)) {
      $this->revisions = $this->result->revisions;
    }
    else {
      $this->revisions = (bool) variable_get('unused_images_revisions', 1);
    }
  }

  /**
   * {@inheritDoc}
   */
  public function fields(array $form, array &$form_state) {
    $form = parent::fields($form, $form_state);

    $fieldset['field_name'] = array(
      '#type' => 'item',
      '#title' => t('Field name'),
      '#markup' => $this->fieldName,
      '#weight' => 5,
    );

    $form['revisions'] = array(
      '#type' => 'checkbox',
      '#title' => t('Check revisions'),
      '#description' => t('Indicate whether to also check all revisions of the selected fields. Not checking this may (considerably) speed up the process but may lead to missing usages in older versions of your content.'),
      '#default_value' => (int) $this->revisions,
      '#weight' => 100,
    );

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(array $settings) {
    $this->initSearch($settings);

    $results = array();

    $fieldInfo = field_info_field($this->fieldName);
    $storageDetails = field_sql_storage_field_storage_details($fieldInfo);
    $storageDetails = $storageDetails['sql'];
    $columnsToSearch = (new UnusedImagesUtilities())->getColumnsByFieldType($fieldInfo['type']);

    // Perform a search per table (current, revision) per column (value,
    // summary, url, ...).
    foreach ($storageDetails as $tableType => $tableDetails) {
      if ($tableType === FIELD_LOAD_CURRENT || $this->revisions) {
        $tableName = key($tableDetails);
        $keys = $this->keyFields[$tableType];
        foreach ($columnsToSearch as $column) {
          $columnName = $tableDetails[$tableName][$column];
          $usages = $this->findUsages($tableName, $columnName, $keys, $this->extensions);
          $results = (new UnusedImagesUtilities())->mergeUsageResults($results, $usages);
        }
      }
    }

    return $results;
  }

  /**
   * {@inheritdoc}}
   */
  protected function initSearch(array $settings) {
    parent::initSearch($settings);

    // Set revisions based on its form value.
    $this->revisions = (bool) $settings['revisions'];
  }

  /**
   * {@inheritDoc}
   */
  protected function createReference($key) {
    $reference = new stdClass();
    $reference->entity_type = $key[0];
    $reference->entity_id = $key[1];
    if (count($key) === count($this->keyFields[FIELD_LOAD_REVISION])) {
      $reference->revision_id = $key[2];
      if ($reference->entity_type === 'node') {
        // For nodes we know how to create the path pointing directly to the
        // revision.
        $reference->url = sprintf('node/%d/revisions/%d/view', $reference->entity_id, $reference->revision_id);
      }
      $reference->language = $key[3];
    }
    else {
      // For nodes we hard code setting the path to prevent a call to
      // entity_load().
      $reference->url = sprintf('node/%d', $reference->entity_id);
      $reference->language = $key[2];
    }

    if (!isset($reference->url)) {
      // Get the url.
      $entities = entity_load($reference->entity_type, $reference->entity_id);
      if (!empty($entities[$reference->entity_id])) {
        $urlInfo = entity_uri($reference->entity_type, $entities[$reference->entity_id]);
        if ($reference->language !== LANGUAGE_NONE) {
          $urlInfo['options']['language'] = $this->getLanguageObject($reference->language);
        }
        $reference->url = url($urlInfo['path'], $urlInfo['options']);
      }
    }
    else {
      // Turn the path into a url.
      $reference->url = url($reference->url, array('language' => $this->getLanguageObject($reference->language)));
    }
    $reference->field = $this->fieldName;
    $reference->delta = end($key);
    return $reference;
  }

  /**
   * {@inheritDoc}
   */
  protected function getQuery($table, $column, array $keys, array $extensions) {
    $query = parent::getQuery($table, $column, $keys, $extensions);
    $query->condition('deleted', 0);
    return $query;
  }

  /**
   * {@inheritDoc}
   */
  protected function createResult(array $results = NULL) {
    $result = parent::createResult($results);
    $result->fieldName = $this->fieldName;
    $result->fieldLabel = $this->fieldLabel;
    if ($results !== NULL) {
      $result->revisions = $this->revisions;
    }
    return $result;
  }

  /**
   * {@inheritDoc}
   */
  protected function getStorageKey() {
    return parent::getStorageKey() . '|' . $this->fieldName;
  }

}
