<?php

/**
 * Class UnusedImagesUtilities
 */
class UnusedImagesUtilities {

  /**
   * Returns a list of fields that may contain textual references to image URIs.
   *
   * @return string[]
   *   Array of field labels that are keyed by the machine name of the field
   *   name. Field labels look like:
   *   "field_name (type) used in: entity_type[ (bundle[, bundle, ...])]"
   */
  public function getFieldsThatMayBeSearched() {
    $options = array();
    $field_types = $this->getFieldTypesThatMayBeSearched();
    foreach ($field_types as $field_type) {
      $options = array_merge($options, $this->getFieldsByType($field_type));
    }
    return $options;
  }

  /**
   * Returns field types that may contain textual references to image URIs.
   *
   * @return string[]
   *   List of of field types (machine name).
   */
  private function getFieldTypesThatMayBeSearched() {
    $options = array();
    if (module_exists('text')) {
      $options[] = 'text';
      $options[] = 'text_long';
      $options[] = 'text_with_summary';
    }
    if (module_exists('link')) {
      $options[] = 'link_field';
    }
    return $options;
  }

  /**
   * Returns all fields that are of the given type.
   *
   * @param string $field_type
   *
   * @return string[]
   *   Array of field labels that are keyed by the machine name of the field
   *   name.
   */
  private function getFieldsByType($field_type) {
    $result = array();
    $fields = field_info_fields();
    foreach ($fields as $field_info) {
      if ($field_info['type'] === $field_type) {
        $label = $field_info['field_name'] . ' (' . $field_info['type'] . ') ' . t('used in:');
        foreach ($field_info['bundles'] as $entity_type => $bundles) {
          $label .= ' ';
          if (count($bundles) === 1 && $entity_type === reset($bundles)) {
            $label .= $entity_type;
          }
          else {
            $label .= $entity_type . ' (' . implode(', ', $bundles) . ')';
          }
        }
        $result[$field_info['field_name']] = $label;
      }
    }
    return $result;
  }

  /**
   * Helper method that captures knowledge of specific field types.
   *
   * @param string $field_type
   *
   * @return string[]
   *   A list of field columns to search for occurrences of the file names.
   */
  public function getColumnsByFieldType($field_type) {
    switch ($field_type) {
      case 'link_field':
        $result = array('url');
        break;
      case 'text_with_summary':
        $result = array('value', 'summary');
        break;
      case 'text':
      case 'text_long':
      default:
        $result = array('value');
        break;
    }
    return $result;
  }

  /**
   * Merges 2 sets of usage results.
   *
   * A simple array_merge() doesn't suffice:
   * -
   * - The set to merge into is keyed by image. The set to merge is not keyed by
   *   image.
   * - When merging results, the sets of primary keys that refer to the same
   *   image needs to be merged using specific logic (especially the isMultiple
   *   property and its consequences for what type of an array (set of keys or
   *   set of key-fields) the property keys will contain.
   *
   * @param array $results
   *   The 1st set of results, keyed by image
   * @param array $newUsages
   *   The 2nd set of results, not keyed.
   * @param \stdClass|null $result
   *   The result object for the 2nd set of results containing meta info that
   *   may be added to each separate result of that 2nd set.
   *
   * @return array
   *   The merged results.
   */
  public function mergeUsageResults(array $results, array $newUsages, stdClass $result = null) {
    foreach ($newUsages as $newUsage) {
      $uri = $newUsage->image;
      if (isset($results[$uri])) {
        // Image is already in result set: add sets of keys.
        $results[$uri]->references = array_merge($results[$uri]->references, $newUsage->references);
      }
      else {
        // Image is not in result set: add new usage to result set.
        $results[$uri] = $newUsage;
      }
    }
    return $results;
  }

  /**
   * Converts a result to a human readable string.
   *
   * @param string|\stdClass $result
   *
   * @return string
   */
  public function resultToString($result) {
    if (is_object($result)) {
      $usageCount = count($result->references);
      $references = array();
      foreach ($result->references as $reference) {
        $referenceString = $reference->entity_type . ' ' . $reference->entity_id;
        if (!empty($reference->revision_id)) {
          $referenceString .= ' (revision: ' . $reference->revision_id . ')';
        }
        $prefix = ' (';
        $postfix = '';
        if (!empty($reference->field)) {
          $referenceString .= $prefix . 'field: ' . $reference->field;
          // This will not add the delta if it is 0. For single valued fields
          // this is just what we want as delta will always be 0. For multi
          // valued fields it is not a big omission if we only leave the delta
          // out when it is 0...
          if (!empty($reference->delta)) {
            $referenceString .= ' Δ' . $reference->delta;
          }
          $prefix = ', ';
          $postfix = ')';
        }
        if (!empty($reference->language)) {
          $referenceString .= $prefix . $reference->language;
          $postfix = ')';
        }
        $referenceString .= $postfix;
        $referenceString = check_plain($referenceString);
        if (!empty($reference->url)) {
          $referenceString = '<a href="' . check_plain($reference->url) . '">' . $referenceString . '</a>';
        }
        $references[] = $referenceString;
      }
      $references = implode(', ', $references);
      return sprintf('<span class="image-found"">%s:</span> <span class="usages-found"">%d usages: %s</span>', $result->image, $usageCount, $references);
    }
    else {
      return (string) $result;
    }
  }

  /**
   * Returns regular expressions to use when searching for image uris that are
   * delimited by (single or double) quotes.
   *
   *
   * In Drupal, many ways exist to reference an image in text fields. Note: in
   * the documentation below it is  assumed that public:// refers to
   * base_url/sites/default/files/, the real folder will be resolved run-time.
   *
   * Variants:
   * - Used in different attributes of different tags:
   *   - <img src="/sites/default/files/img_7893.jpg?itok=Ul4ohBEJ">
   *   - <picture><source srcset="https://lescamelias.eu/sites/default/files/styles/flexslider_medium/public/puy-de-come.jpg?itok=W9Ez8ADL 1x, http://localhost/les-camelias/sites/default/files/styles/flexslider_full/public/puy-de-come.jpg?itok=ruByVCL3 2x" media="(min-width:660px)">
   *   - <a href="/sites/default/files/img_7893.jpg?itok=Ul4ohBEJ">
   *   - <li data-thumb="https://lescamelias.eu/sites/default/files/styles/flexslider_small/public/le-mont-dore-winter.jpg?itok=8-x1wdlc">
   * - Located in a sub folder of public://:
   *   - src="/sites/default/files/2018-12/user-3/img_7893.jpg?itok=Ul4ohBEJ"
   * - Used relative to the base path (instead of server root relative) (can
   *   lead to problems on multilingual sites with language prefix: always
   *   handed over to Drupal, never served directly):
   *   - src="sites/default/files/img_7893.jpg?itok=Ul4ohBEJ"
   * - Used relative to "current" page:
   *   - src="../sites/default/files/img_7893.jpg?itok=Ul4ohBEJ"
   * - Used as image derivative (image style):
   *   - src="/sites/default/files/styles/thumbnail/public/img_7893.jpg?itok=Ul4ohBEJ"
   * - Used without itok (when disabled):
   *   - src="/sites/default/files/styles/thumbnail/public/img_7893.jpg"
   * - Local/test/stage versions, work when Pathologic has been installed:
   *   - src="/les-camelias/sites/default/files/img_7893.jpg?itok=Ul4ohBEJ"
   * - Full domain and protocol references, also for local, test, stage. Work
   *   with Pathologic (or fail for localhost references for non-developers):
   *   - src="//localhost/les-camelias/sites/default/files/img_7893.jpg?itok=3Wo1lxT5"
   *   - src="http://localhost/les-camelias/sites/default/files/img_7893.jpg?itok=3Wo1lxT5"
   *   - scr="https://localhost/les-camelias/sites/default/files/img_7893.jpg?itok=3Wo1lxT5"
   *   - src="//lescamelias.eu/sites/default/files/img_7893.jpg?itok=3Wo1lxT5"
   *   - src="http://lescamelias.eu/sites/default/files/img_7893.jpg?itok=3Wo1lxT5"
   *   - src="https://lescamelias.eu/sites/default/files/img_7893.jpg?itok=3Wo1lxT5"
   * - Used on private or temporary:
   *   - src="/system/files/img_7893.jpg?itok=Ul4ohBEJ"
   *   - src="/system/temporary/img_7893.jpg?itok=Ul4ohBEJ"
   * - Attributes may be placed within single quotes:
   *   - src='/sites/default/files/img_7893.jpg?itok=Ul4ohBEJ'
   * - Other query parameters. If this is not an image but a document, no itok
   *   parameter will be present, but others may be, e.g a "version number" to
   *   bypass caching or a parameter for some download statistic.
   *
   * "Variants" not taken into account:
   * - Used in filter tags:
   *   - Media tags are based on fid (managed file), so do not contain image
   *     paths and are already taken care of via the Managed Files usage finder.
   *   - No known tags that use file names directly like e.g. [pubic://...]. If
   *     we learn of tags that do something like this, we might change the code
   *     to incorporate it.
   * - Plain text URLs that are converted to links using the 'Convert URLs into
   *   links' filter as this filter will not change a URL into an <img> tag, so
   *   this feature is not likely to be used with images. NOTE: this may
   *   actually be one of the few if not the only place where we really assume
   *   to be looking for images, not documents in general, so we might
   *   reconsider this.
   * - Attributes may be specified without quotes (i.e, I guess, if they do not
   *   contain spaces, > and other special characters):
   *   - src=/sites/default/files/img_7893.jpg?itok=Ul4ohBEJ
   *
   *  Regular expression:
   *  - ="(([^"?]+\.(jpg|jpeg|png|gif|))(\?[^"])?)"
   *  - ='(([^'?]+\.(jpg|jpeg|png|gif|))(\?[^'])?)'
   *    - match 0 = full quoted attribute value + quotes + =-sign: ignore.
   *    - match 1 = full uri with query: parse_url() and custom code will be
   *      used to see if it matches one of the above variants.
   *    - match 2 = uri without query: ignore.
   *    - match 3 = extension: ignore.
   *    - match 4 = optional query (including ?): ignore.
   * @param string[] $extensions
   *
   * @return string[]
   */
  public function getRegExpsForQuoteDelimitedImageUris(array $extensions) {
    // Init regular expressions.
    //    *  - /="([^"?]+\.(jpg|jpeg|png|gif)(\?[^"]+)?)"/
    //    *  - /='([^'?]+\.(jpg|jpeg|png|gif)(\?[^']+)?)'/
    $regExpExtensions = '\.(' . implode('|', $extensions) . ')';
    $result = array(
      '/="([^"?]+' . $regExpExtensions . '(\?[^"]+)?)"/',
      "/='([^'?]+" . $regExpExtensions . "(\?[^']+)?)'/"
    );
    return $result;
  }

  /**
   * Returns regular expressions to use when searching for image uris that are
   * embedded in text.
   *
   * How to recognize references to images:
   * We cannot use the same restriction as in text fields, namely that an image
   * should appear as an attribute value and thus appears between quotes. We
   * also cannot use the method the url filter uses, see _filter_url(), as that
   * assumes that urls are absolute and thus start with a protocol or the domain
   * name.
   *
   * So we have do it differently:
   * - Find all occurrences of
   *   * A set of accepted characters, we base this set on the rules about uris
   *     and file names on local file systems, further restricted by disallowing
   *     common punctuation that may be expected to be a delimiter, not part of
   *     the image name, especially in texts with plain URL's that are
   *     transformed into links by a filter.
   *   * Ending with a dot followed by one of the selected extensions.
   *   * @TODO: Not followed by another extension.
   *
   * Note: https://perishablepress.com/stop-using-unsafe-characters-in-urls/ was
   * used to get the initial list of acceptable characters. But contrary to its
   * advice, it is common usage to allow unicode characters outside the ascii
   * range as browsers will encode them when sending them over the http
   * protocol. So I compiled a list of non-acceptable characters instead of
   * acceptable characters. Of course unsafe/reserved characters with a special
   * meaning (e.g. ':' and '/') are to be allowed as well. Whereas the safe
   * character "'" is often used as delimiter, so will not be accepted.
   *
   * @param string[] $extensions
   *
   * @return string[]
   */
  public function getRegExpsForEmbeddedImageUris(array $extensions) {
    // Characters not accepted:
    // ASCII Control characters	00-1F hex (0-31 decimal) and 7F (127 decimal.)
    $control = '\\x00-\\x1F\\x7F';

    // Reserved characters: ; / ? : @ = &
    // Of these, '@', ':' and '/' have a special meaning in building a uri and
    // thus should be accepted. '?', '=' and '&' have a special meaning in the
    // query part, which we ignore, so we will not accept these.
    $reserved = ';?=&@';

    // Unsafe characters: space and " < > # % { } | \ ^ ~ [ ] `
    $unsafe = ' "<>#%{}|\\\\\\^~[\\]`';

    // We add ' and , as these will probably be delimiting the uri, not part of
    // it.
    $delimiters = ",'„";

    // We also add * as it is forbidden in file names on Windows and, if found
    // anyway, probably serves as search wildcard.
    $forbidden = '*';

    // List of accepted characters is every character except those listed in
    // the regular expression ranges above.
    $accepted = "[^$control$reserved$unsafe$delimiters$forbidden]";
    $extensions = '\.(' . implode('|', $extensions) . ')';

    return array(
      "/($accepted+$extensions)/"
    );
  }

}
