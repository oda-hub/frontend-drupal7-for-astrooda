<?php
/** @noinspection HtmlUnknownTarget */

/**
 * Class UnusedImagesUsageFinderBase
 */
abstract class UnusedImagesUsageFinderBase extends UnusedImagesFinderBase {

  /** @var string $usageType */
  private $usageType;

  /**
   * @return string
   */
  public function getUsageType() {
    return $this->usageType;
  }

  /**
   * @param string $usageType
   */
  protected function setUsageType($usageType) {
    $this->usageType = $usageType;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return t('Finds usages of images in %usage_type', array('%usage_type' => $this->getUsageType()));
  }

  /**
   * {@inheritDoc}
   */
  public function getHelp() {
    return t('Searches for references to images in %usage_type', array('%usage_type' => $this->getUsageType()));
  }

  /**
   * {@inheritDoc}
   */
  protected function getRunDetails(array $fieldset) {
    drupal_add_css('.image-found, .usages-found {display: inline-block;} .image-found {min-width:30em;}', 'inline');
    $fieldset['usage_type'] = array(
      '#type' => 'item',
      '#title' => t('Searched for usages in'),
      '#markup' => $this->result->usageType,
      '#weight' => 800,
    );
    $fieldset['extensions'] = array(
      '#type' => 'item',
      '#title' => t('Extensions searched for'),
      '#markup' => implode("<br>\n", $this->result->extensions),
      '#weight' => 810,
    );
    $fieldset['files_found'] = array(
      '#type' => 'item',
      '#title' => t('Usages found'),
      '#markup' => !empty($this->result->results)
        ? implode("<br>\n", array_map(array($this, 'resultToString'), $this->result->results))
        : t('No usages were found with the above settings'),
      '#weight' => 820,
    );

    return $fieldset;
  }

  /**
   * Creates an object with information about the entity referring to an image.
   *
   * @param int|string|array $key
   *   The single primary key (e,g, fid (managed file), bid (block), or name
   *   (variable)) or array of primary keys (text field) for this reference.
   *
   * @return stdClass
   *   An object with properties entity_type(*), entity_id(*), revision_id, url,
   *   language, field, and delta (* = required property).
   */
  abstract protected function createReference($key);

  /**
   * Returns a language object for the given language code.
   *
   * @param string $language
   *
   * @return object|null
   */
  protected function getLanguageObject($language) {
    $languages = language_list();
    return isset($languages[$language]) ? $languages[$language] : NULL;
  }

  /**
   * {@inheritDoc}
   */
  public function resultToString($result) {
    return (new UnusedImagesUtilities())->resultToString($result);
  }

  /**
   * {@inheritDoc}
   */
  protected function createResult(array $results = NULL) {
    $result = parent::createResult($results);
    $result->usageType = $this->getUsageType();
    return $result;
  }

  /**
   * {@inheritDoc}
   */
  protected function getStorageKey() {
    return 'unused_images|' . static::class;
  }

}
