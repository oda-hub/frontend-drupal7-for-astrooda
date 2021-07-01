<?php

/**
 * UnusedImagesUsageFinderLocales looks for references to images in translatable
 * strings and their translations.
 */
class UnusedImagesUsageFinderLocales extends UnusedImagesUsageFinderTableColumn {

  /**
   * UnusedImagesUsageFinderVariables constructor.
   *
   * @param array $extensions
   */
  public function __construct(array $extensions) {
    $this->setUsageType('Locales (translatable strings and its translations)');
    parent::__construct($extensions);
  }

  /**
   * {@inheritDoc}
   */
  protected function execute(array $settings) {
    $this->initSearch($settings);
    $results = array();
    if (module_exists('locale')) {
      $results = $this->findUsages('locales_source', 'source', array('lid'), $this->extensions);
      $results = (new UnusedImagesUtilities())->mergeUsageResults($results,
        $this->findUsages('locales_target', 'translation', array('language', 'lid', 'plural'), $this->extensions));
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
      $reference->entity_type = 'locales_source';
      $reference->entity_id = $key[0];
      $reference->language = language_default('language');
    }
    else {
      $reference->entity_type = 'locales_target';
      $reference->entity_id = $key[1];
      $reference->language = $key[0];
    }
    if (module_exists('locale')) {
      $reference->url = url(sprintf('admin/config/regional/translate/edit/%d', $reference->entity_id));
    }
    return $reference;
  }

}
