<?php

/**
 * UnusedImagesUsageFinderVariables looks for references to images in variables.
 */
class UnusedImagesUsageFinderVariables extends UnusedImagesUsageFinderTableColumn {

  /**
   * UnusedImagesUsageFinderVariables constructor.
   *
   * @param array $extensions
   */
  public function __construct(array $extensions) {
    $this->setUsageType('Variables');
    parent::__construct($extensions);
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(array $settings) {
    $this->initSearch($settings);
    $results = $this->findUsages('variable', 'value', array('name'), $this->extensions);
    if (module_exists('variable_store')) {
      $results = (new UnusedImagesUtilities())->mergeUsageResults($results,
        $this->findUsages('variable_store', 'value', array('realm', 'realm_key', 'name'), $this->extensions));
    }
    return $results;
  }

  /**
   * {@inheritdoc}
   */
  protected function setRegExps(array $settings) {
    $this->regExps = (new UnusedImagesUtilities())->getRegExpsForEmbeddedImageUris($settings['extensions']);
  }

  /**
   * {@inheritDoc}
   */
  protected function createReference($key) {
    $reference = new stdClass();
    if (count($key) === 1) {
      $reference->entity_type = 'variable';
      $reference->entity_id = $key[0];
      $reference->language = language_default('language');
    }
    else {
      $reference->entity_type = 'variable_store';
      $reference->entity_id = $key[2];
      if ($key[0] === 'language') {
        $reference->language = $key[1];
      }
      else {
        $reference->language = language_default('language');
      }
    }
    if (module_exists('variable_admin')) {
      $reference->url = url(sprintf('admin/config/system/variable/edit/%s', urlencode($reference->entity_id)), array('language' => $this->getLanguageObject($reference->language)));
    }
    return $reference;
  }

}
