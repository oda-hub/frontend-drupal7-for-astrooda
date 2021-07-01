<?php

/**
 * Class UnusedImagesUsageFinderTableColumn searches in text columns (blob of
 * text) for usages of images.
 *
 * In Drupal, many ways exist to reference an image in text fields. Note: in the
 * documentation below it is  assumed that public:// refers to
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
 * - Used relative to the base path (instead of server root relative) (can lead
 *   to problems on multilingual sites with language prefix: always handed over
 *   to Drupal, never served directly):
 *   - src="sites/default/files/img_7893.jpg?itok=Ul4ohBEJ"
 * - Used relative to "current" page:
 *   - src="../sites/default/files/img_7893.jpg?itok=Ul4ohBEJ"
 * - Used as image derivative (image style):
 *   - src="/sites/default/files/styles/thumbnail/public/img_7893.jpg?itok=Ul4ohBEJ"
 * - Used without itok (when disabled):
 *   - src="/sites/default/files/styles/thumbnail/public/img_7893.jpg"
 * - Local/test/stage versions, work when Pathologic has been installed:
 *   - src="/les-camelias/sites/default/files/img_7893.jpg?itok=Ul4ohBEJ"
 * - Full domain and protocol references, also for local, test, stage. Work with
 *   Pathologic (or fail for localhost references for non-developers):
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
 *   - Media tags are based on fid (managed file), so do not contain image paths
 *     and are already taken care of via the Managed Files usage finder.
 *   - No known tags that use file names directly like e.g. [pubic://...]. If we
 *     learn of tags that do something like this, we might change the code to
 *     incorporate it.
 * - Plain text URLs that are converted to links using the 'Convert URLs into
 *   links' filter as this filter will not change a URL into an <img> tag, so
 *   this feature is not likely to be used with images. NOTE: this may actually
 *   be one of the few if not the only place where we really assume to be
 *   looking for images, not documents in general, so we might reconsider this.
 * - Attributes may be specified without quotes (i.e, I guess, if they do not
 *   contain spaces, > and other special characters):
 *   - src=/sites/default/files/img_7893.jpg?itok=Ul4ohBEJ
 *
 *  Regular expression:
 *  - ="(([^"?]+\.(jpg|jpeg|png|gif|))(\?[^"])?)"
 *  - ='(([^'?]+\.(jpg|jpeg|png|gif|))(\?[^'])?)'
 *    - match 0 = full quoted attribute value + quotes + =-sign: ignore.
 *    - match 1 = full uri with query: parse_url() and custom code will be used
 *      to see if it matches one of the above variants.
 *    - match 2 = uri without query: ignore.
 *    - match 3 = extension: ignore.
 *    - match 4 = optional query (including ?): ignore.
 */
abstract class UnusedImagesUsageFinderTableColumn extends UnusedImagesUsageFinderBase {

  /**
   * @var string[]
   */
  protected $regExps;

  /**
   * List with info about stream wrapper patterns to be searched for.
   *
   * Each entry is keyed by the wrapper name and contains an array with keys:
   * - path: the path part of the url to the stream wrapper, ends with a /.
   *   E.g. site/default/files/ or system/private/.
   * - path_len: the string length of url_path
   * - uri: the uri to the root folder of the stream wrapper. E.g. public://.
   * - styles_uri: the uri to the styles folder within the stream wrapper,
   *   ends with a /. E.g. public://styles/.
   * - styles_uri_len: The string length of styles_uri
   * - wrapper_folder: the wrapper folder part in the uri of a derivative image,
       e.g. /public/
   * - wrapper_folder_len: string length of wrapper_folder
   * @var array[]
   */
  protected $streamWrappers;

  /**
   * List of parse_url() results keyed by the base path fed to parse_url().
   *
   * @var array[]
   */
  protected $baseUrls;

  /**
   * List of base paths as keys and their string length as value.
   *
   * Base paths are the path on a given host to the site home. They do not start
   * but they do end with a /. Examples: / => 1, my-site-dev/ => 12.
   *
   * @var array
   */
  protected $basePaths;

  /**
   * {@inheritDoc}
   */
  public function fields(array $form, array &$form_state) {
    $form = parent::fields($form, $form_state);

    /** @noinspection HtmlUnknownTarget */
    $form['unused_images_base_urls'] = array(
      '#type' => 'textarea',
      '#title' => t('All base URLs for this site'),
      '#default_value' => implode("\n", variable_get('unused_images_base_urls', null)),
      '#description' => t('The list of base URLs on which this site is or was available.'),
      '#disabled' => TRUE,
      '#weight' => 110,
    );

    $options = array();
    foreach (file_get_stream_wrappers() as $scheme => $wrapperInfo) {
      $finder = new UnusedImagesImageFinder($scheme);
      $options[$finder->getInstanceKey()] = $finder->getDescription();
    }
    $form['unused_images_paths'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Image finder paths'),
      '#description' => t('Overview of places where to look for image files.'),
      '#default_value' => variable_get('unused_images_paths', array()),
      '#options' => $options,
      '#disabled' => TRUE,
      '#weight' => 115,
    );

    return $form;
  }

  /**
   * Initializes settings used during the search.
   *
   * As the search is done in a loop with a possibly very large number of
   * repeats, we want to prepare the data we need in a way that allows for easy
   * access.
   *
   * Drupal sets the following global variables:
   * - $base_url = "http://localhost/les-camelias"
   * - $base_root = "http://localhost"
   * - $base_path = "/les-camelias/"
   *
   * @param array $settings
   *   The search settings.
   */
  protected function initSearch(array $settings) {
    global $base_url;

    $this->setRegExps($settings);

    // Init base urls and paths.
    $this->baseUrls = variable_get('unused_images_base_urls', null);
    $this->baseUrls = array_combine($this->baseUrls, array_map(function($url) {
      $parts = $this->parseUrl($url);
      $parts['path_len'] = strlen($parts['path']);
      return $parts;
    }, $this->baseUrls));

    $this->basePaths = array();
    foreach ($this->baseUrls as $baseUrlParts) {
      $this->basePaths[$baseUrlParts['path']] = $baseUrlParts['path_len'];
    }
    // Sort by descending string length.
    arsort($this->basePaths);

    // Init stream wrappers
    $this->streamWrappers = array();
    $instanceKeys = variable_get('unused_images_paths', array());
    foreach ($instanceKeys as $key) {
      /** @var \UnusedImagesImageFinder $finder */
      $finder = unused_images_get_action_instance($key);
      $path = $finder->getPath();
      if (substr($path, -strlen('://')) === '://') {
        $scheme = substr($path, 0, -strlen('://'));
        $stream = file_stream_wrapper_get_instance_by_scheme($scheme);
        if ($stream) {
          /** @var DrupalLocalStreamWrapper $stream */
          $url = $stream->getExternalUrl();
          if (substr($url, 0, strlen($base_url)) === $base_url) {
            $url = substr($url, strlen($base_url . '/'));
            // In multilingual sites the language code may be prepended to system
            // handled wrappers (private, temporary): e.g. system/files/ becomes
            // nl/system/files/.
            $systemPos = strpos($url, 'system/');
            if ($systemPos !== FALSE) {
              $url = substr($url, $systemPos);
            }
            $this->streamWrappers[$scheme] = array(
              'path' => $url,
              'uri' => "$scheme://",
              'styles_uri' => "$scheme://styles/",
              'wrapper_folder' => "/$scheme/",
            );
            $this->streamWrappers[$scheme] += array(
              'path_len' => strlen($this->streamWrappers[$scheme]['path']),
              'styles_uri_len' => strlen($this->streamWrappers[$scheme]['styles_uri']),
              'wrapper_folder_len' => strlen($this->streamWrappers[$scheme]['wrapper_folder']),
            );
          }
        }
      }
    }
  }

  /**
   * Sets the regular expressions to use for searching for references to images.
   *
   * This method sets regular expressions for the case where a reference to an
   * image is expected be enclosed in (single or double) quotes.
   *
   * @param array $settings
   *   The settings for this search, specifically the list of extensions to
   *   search for is used.
   */
  protected function setRegExps(array $settings) {
    $this->regExps = (new UnusedImagesUtilities())->getRegExpsForQuoteDelimitedImageUris($settings['extensions']);
  }

  /**
   * Find usages of images in a given column of a given table.
   *
   * @param string $table
   *   The name of the table to search in.
   * @param string $column
   *   The name of the column within the table to search in.
   * @param array $keys
   *   The field names that make up the primary key of this table.
   * @param array $extensions
   *   List of extensions to search for.
   *
   * @return object[]
   *   A list of informational objects about images referred to in the given
   *   column of the given table. Each object will have these properties:
   *   - image (string): the uri of an image referred to.
   *   - keys (string|string[]): an (array of) imploded list(s) of values for
   *     the primary key(s) of the record(s) were a usage of this image was
   *     found.
   *   - isMultiple: if keys contains multiple or a single usage.
   */
  protected function findUsages($table, $column, array $keys, array $extensions) {
    $query = $this->getQuery($table, $column, $keys, $extensions);
    $rows = $query->execute();
    $results = array();

    // Search for usages in all returned rows..
    foreach ($rows as $row) {
      // Search for usages in the given text column of the row.
      $usages = $this->extractUsages($row->$column);

      // Add a result for each image found in this row.
      if (!empty($usages)) {
        // For each result we also store the keys of the record(s) in which the
        // usage was found.
        $keyValues = array();
        foreach ($keys as $key) {
          $keyValues[] = $row->$key;
        }
        $reference = $this->createReference($keyValues);

        foreach ($usages as $image) {
          if (isset($results[$image])) {
            // Image has already been used in (an)other record(s): add this set
            // of keys to the set of already stored keys.
            $result = $results[$image];
            $result->references[] = $reference;
          }
          else {
            // Image has not already been used in another record: set the keys
            // of this record as 'keys'.
            $result = new stdClass();
            $result->image = $image;
            $result->references = array($reference);
          }
          $results[$image] = $result;
        }
      }
    }
    return $results;
  }

  /**
   * Returns the query to execute.
   *
   * @param string $table
   * @param string $column
   * @param array $keys
   * @param array $extensions
   *
   * @return \QueryConditionInterface|\SelectQuery|\SelectQueryInterface
   */
  protected function getQuery($table, $column, array $keys, array $extensions) {
    $fields = $keys;
    $fields[] = $column;

    // Create the query.
    $query = db_select($table)
      ->fields($table, $fields);
    // Add 'like' conditions to limit the amount of data transferred.
    $or = db_or();
    foreach ($extensions as $extension) {
      $or->condition($column, '%.' . $extension . '%', 'LIKE');
    }
    $query->condition($or);
    return $query;
  }

  /**
   * Extract usages of image uris in a given text.
   *
   * @param string $text
   *   The text to search for image uris, the whole content of 1 field value.
   *
   * @return string[]
   *   The list of found image uris in wrapper notation, keyed by that uri to
   *   prevent duplicates.
   */
  protected function extractUsages($text) {
    $results = array();

    // We first match on the regular expressions to extract URLs that are
    // specified as an attribute value (="<value>") and that do end on 1 of the
    // extensions (though possibly followed by a query part).
    foreach ($this->regExps as $regExp) {
      if (preg_match_all($regExp, $text, $matches)) {
        foreach ($matches[1] as $match) {
          // Further narrow down matches.
          $usage = $this->processMatch($match);
          if ($usage) {
            $results[$usage] = $usage;
          }
        }
      }
    }

    return $results;
  }

  /**
   * Further processes uris that match the regular expression.
   *
   * The regular expression is rather broad, so we have to filter matches
   * further by looking:
   * - If they refer to "local" uris, ie. not to other sites.
   * - If the path part refers to one of the searched wrappers (public, private,
   *   ..).
   * - To return a usage, derivative uris must be changed to their original
   *   ones.
   *
   * Note: parse_url() returns the following info:
   * - URL: http://lescamelias.eu/sites/default/files/img.jpg
   *   result: array ( 'scheme' => 'http', 'host' => 'lescamelias.eu', 'path' => '/sites/default/files/img.jpg', )
   * - URL: //lescamelias.eu/sites/default/files/img.jpg
   *   result: array ( 'host' => 'lescamelias.eu', 'path' => '/sites/default/files/img.jpg', )
   * - URL: http://lescamelias.eu:80/sites/default/files/img.jpg
   *   result: array ( 'scheme' => 'http', 'host' => 'lescamelias.eu', 'port' => 80, 'path' => '/sites/default/files/img.jpg', )
   * - URL: localhost://lescamelias/sites/default/files/img.jpg
   *   result: array ( 'scheme' => 'localhost', 'host' => 'lescamelias', 'path' => '/sites/default/files/img.jpg', )
   * - URL: /sites/default/files/img.jpg
   *   result: array ( 'path' => '/sites/default/files/img.jpg', )
   * - URL: sites/default/files/img.jpg
   *   result: array ( 'path' => 'sites/default/files/img.jpg', )
   * - URL: http://lescamelias.eu/
   *   result: array ( 'scheme' => 'http', 'host' => 'lescamelias.eu', 'path' => '/', )
   * - URL: http://lescamelias.eu
   *   result: array ( 'scheme' => 'http', 'host' => 'lescamelias.eu', )
   * - URL: //lescamelias.eu
   *   result: array ( 'host' => 'lescamelias.eu', )
   * - URL: ../images/img.jpg
   *   result: array ( 'path' => '../images/img.jpg', )
   *
   * @param string $match
   *   An attribute value or part of a variable, probably containing a uri,
   *   ending on one of the extensions to search for, optionally followed by
   *   a query part.
   *
   * @return string|false
   *   The uri of a referenced image or false if it turned out not to be a
   *   reference to an image.
   */
  protected function processMatch($match) {
    $parts = $this->parseUrl($match);
    $uri = FALSE;
    if (is_array($parts)) {
      if ($this->isDrupalStreamWrapper($parts['scheme'])) {
        $uri = $match;
        $wrapper = $parts['scheme'];
      }
      elseif (!empty($parts['path']) && $this->isConsideredLocal($parts, $path)) {
        $uri = $this->toStreamWrapperUri($path, $wrapper);
      }
      if (!empty($uri)) {
        /** @noinspection PhpUndefinedVariableInspection */
        $uri = $this->derivativeToOriginal($uri, $wrapper);
      }
    }
    return $uri;
  }

  /**
   * Returns whether $scheme is a valid Drupal stream wrapper scheme.
   *
   * @param string|null $scheme
   *
   * @return bool
   */
  private function isDrupalStreamWrapper($scheme) {
    return is_string($scheme) && file_stream_wrapper_valid_scheme($scheme);
  }

  /**
   * Checks if a parsed url should be considered local.
   *
   * Considered local are:
   * [- Url with a (registered) Drupal stream wrapper as scheme.] handled elsewhere
   * - Relative urls.
   * - Site root relative urls starting with one of the given base paths.
   * - Absolute or protocol relative urls: match against the list of base URLs
   *   considered local.
   *
   * @param array $imgUrlParts
   *   A url parsed into its possible parts:
   *   - scheme: http, https, or a stream wrapper like public or private
   *   - host: the domain name
   *   - port: not set or an integer
   *   - user: ignored
   *   - pass: ignored
   *   - path (*): the path (on the host) to the image.
   *   - query: part after the question mark, ignored
   *   - fragment: ignored
   * @param string $path
   *   Out: The path relative to the site base path.
   *
   * @return bool
   *   True if the parsed url is considered referring to the local site,
   *   false otherwise.
   */
  private function isConsideredLocal(array $imgUrlParts, &$path) {
    $result = FALSE;
    $path = $imgUrlParts['path'];
    if ($path[0] !== '/') {
      // Site base relative path: always considered local.
      $result = TRUE;
    }
    elseif (!isset($imgUrlParts['host'])) {
      // Site root relative: check if it starts with one of the base paths.
      foreach ($this->basePaths as $basePath => $len) {
        if (substr($path, 0, $len) === $basePath) {
          // It does: get the path relative to the base.
          $path = substr($path, $len);
          $result = TRUE;
          break;
        }
      }
    }
    else {
      // Absolute or protocol relative url: check it with parts of the base urls
      // to find a match.
      // - scheme: we assume that if content will be delivered, i.e. not a 301
      //   or 302 redirect, the same content will be delivered regardless
      //   whether the protocol is http or https. So to determine whether the
      //   given path is local we can ignore the scheme.
      // - host: hosts should be equal (case insensitive)
      // - port: 80 and 443 are considered equal, other port numbers are
      //   considered different.
      // - path: the start of the image path must equal the path part of the
      //   base url
      foreach ($this->baseUrls as $baseUrlParts) {
        if ($imgUrlParts['host'] === $baseUrlParts['host']
          && $imgUrlParts['port'] === $baseUrlParts['port']
          && substr($path, 0, $baseUrlParts['path_len']) === $baseUrlParts['path']) {
          // We do consider the path local: get the path relative to the base.
          $path = substr($path, $baseUrlParts['path_len']);
          $result = TRUE;
          break;
        }
      }
    }
    return $result;
  }

  /**
   * Checks if a (site root relative) path is in 1 of the stream wrapper paths.
   *
   * This method uses the list of stream wrappers to be searched.
   *
   * @param string $path
   *   A path relative to the site base.
   * @param string $wrapper
   *   Out: the name of the stream wrapper (e.g. public) or the empty string if
   *   the uri is not in a stream wrapper path.
   *
   * @return string
   *   The path converted to a stream wrapper uri, if the path is covered by a
   *   stream wrapper, the path itself otherwise.
   */
  private function toStreamWrapperUri($path, &$wrapper) {
    $uri = $path;
    $wrapper = '';
    foreach ($this->streamWrappers as $name => $streamWrapper) {
      if (substr($path, 0, $streamWrapper['path_len']) === $streamWrapper['path']) {
        $uri = $streamWrapper['uri'] . substr($path, $streamWrapper['path_len']);
        $wrapper = $name;
        break;
      }
    }
    return $uri;
  }

  /**
   * Returns the uri to the original image.
   *
   * Examples:
   * - public://2018/03/img.jpg => public://2018/03/img.jpg
   * - public://styles/thumbnail/public/2018/03/img.jpg => public://2018/03/img.jpg
   *
   * @param string $wrapperUri
   *   A (stream wrapper) uri that may refer to a derivative image or to an
   *   original image.
   * @param string $wrapper
   *   The name of the stream wrapper of wrapperUri, e.g. public.
   *
   * @return string
   *   The uri to the original image.
   */
  private function derivativeToOriginal($wrapperUri, $wrapper) {
    if (isset($this->streamWrappers[$wrapper])) {
      $streamWrapper = $this->streamWrappers[$wrapper];
      if (substr($wrapperUri, 0, $streamWrapper['styles_uri_len']) === $streamWrapper['styles_uri']) {
        // Remove the {wrapper}://styles/{style_name}/{wrapper}/ part of the uri
        // and make it a uri again by prepending {wrapper}://
        $originalImagePos = strpos($wrapperUri, $streamWrapper['wrapper_folder']) + $streamWrapper['wrapper_folder_len'];
        $wrapperUri = $streamWrapper['uri'] . substr($wrapperUri, $originalImagePos);
      }
    }
    return $wrapperUri;
  }

  /**
   * Variant on parse_url().
   *
   * The following modifications are made to the result of parse_url:
   * - scheme is set to null if not set.
   * - host is lower cased.
   * - port is set to null if not set or if it equals 80 or 443.
   * - path is set to / if not set.
   * This assures that all used keys exist and default port numbers are
   * nullified.
   *
   * @param string $url
   *   The (partial) url to parse.
   *
   * @return array
   *    A keyed array as {@see parse_url()} returns.
   */
  protected function parseUrl($url) {
    $parts = parse_url($url) + array(
        'scheme' => NULL,
        'port' => NULL,
        'path' => '/',
      );
    if (isset($parts['host'])) {
      $parts['host'] = strtolower($parts['host']);
    }
    if ($parts['port'] === 80 || $parts['port'] === 443) {
      $parts['port'] = NULL;
    }
    return $parts;
  }

}
