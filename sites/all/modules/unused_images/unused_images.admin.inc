<?php

/** @noinspection PhpUnused
 *
 * Page callback for 'admin/config/media/unused-images'.
 *
 * @return array
 *   A render array containing the status overview for the
 *   'admin/config/media/unused-images' page.
 *
 * @throws \Exception
 */
function unused_images_overview() {
  if (!empty($_REQUEST['all']) && in_array($_REQUEST['all'], array('unused_images_paths', 'unused_images_usages', 'unused_images_differences'))) {
    if ($_REQUEST['all'] === 'unused_images_differences') {
      $instanceKeys = array((new UnusedImagesUnusedImages())->getInstanceKey(), (new UnusedImagesNotExistingImages())->getInstanceKey());
    }
    else {
      $instanceKeys = array_filter(variable_get($_REQUEST['all'], array()));
    }
    if (!empty($instanceKeys)) {
      // Run/refresh all instances of the given type
      foreach ($instanceKeys as $key) {
        $finder = unused_images_get_action_instance($key);
        $finder->submitBatch();
      }
    }
    // Goto current page *without* query string.
    drupal_goto(current_path());
  }
  $page = array();
  $page['status_overview_table'] = unused_images_get_search_tasks_overview_table();
  return $page;
}

/**
 * Returns a table containing an status overview of the search tasks.
 *
 * @return array
 *
 * @throws \Exception
 */
function unused_images_get_search_tasks_overview_table() {
  $header = array(
    t('Task'),
    t('Status'),
    t('Operations'),
  );

  $rows = array_merge(
    unused_images_get_finder_status_rows('Image finders', 'unused_images_paths'),
    unused_images_get_finder_status_rows('Usage finders', 'unused_images_usages')
  );

  // Create overview of differences results.
  $rows[] = array(
    'data' => array(
      array(
        'data' => t('Differences found'),
        'header' => TRUE,
        'colspan' => 3,
      ),
    ),
    'no_striping' => TRUE,
  );
  $differences1 = new UnusedImagesUnusedImages();
  $rows[] = array(
    'data' => array(
      $differences1->getDescription(),
      $differences1->getStatusText(),
      theme('links__ctools_dropbutton', array(
        'links' => $differences1->getActions(),
        'attributes' => array('class' => array('links', 'inline'))
      )),
    ),
    'no_striping' => TRUE,
  );
  $differences2 = new UnusedImagesNotExistingImages();
  $rows[] = array(
    'data' => array(
      $differences2->getDescription(),
      $differences2->getStatusText(),
      theme('links__ctools_dropbutton', array(
        'links' => $differences2->getActions(),
        'attributes' => array('class' => array('links', 'inline'))
      )),
    ),
    'no_striping' => TRUE,
  );
  // Add run all difference finders row.
  if ($differences1->hasRun() !== $differences2->hasRun()) {
    $title = t('Run/refresh all');
  }
  elseif ($differences1->hasRun()) {
    $title = t('Refresh all');
  }
  else {
    $title = t('Run all');
  }
  $rows[] = array(
    'data' => array(
      $title,
      t(''),
      theme('links__ctools_dropbutton', array(
        'links' => array( 'run'  => array(
          'title' => $title,
          'href' => 'admin/config/media/unused-images',
          'query' => array('all' => 'unused_images_differences'),
        )),
        'attributes' => array('class' => array('links', 'inline'))
      )),
    ),
    'no_striping' => TRUE,
  );



  return array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $rows,
    '#attributes' => array('id' => 'admin-unused-images-search_tasks-overview'),
    '#empty' => t('No status overview available.'),
  );
}

/**
 * Returns a list of status rows for the given type of finder.
 *
 * @param string $header
 * @param string $variable
 *
 * @return array
 *
 * @throws \Exception
 */
function unused_images_get_finder_status_rows($header, $variable) {
  $rows = array();
  $rows[] = array(
    'data' => array(
      array(
        'data' => t($header),
        'header' => TRUE,
        'colspan' => 3,
      ),
    ),
    'no_striping' => TRUE,
  );
  $instanceKeys = array_filter(variable_get($variable, array()));
  if (empty($instanceKeys)) {
    /** @noinspection HtmlUnknownTarget */
    $rows[] = array(
      'data' => array(
        array(
          'data' => t('No @finder_type selected yet, please visit the <a href="@url">@Settings</a> page first.',
            array(
              '@finder_type' => $header,
              '@url' => url('admin/config/media/unused-images/settings'),
              '@Settings' => t('Settings'),
            )
          ),
          'colspan' => 3,
        ),
      ),
      'no_striping' => TRUE,
    );
  }
  else {
    $countRun = 0;
    foreach ($instanceKeys as $key) {
      $finder = unused_images_get_action_instance($key);
      $rows[] = array(
        'data' => array(
          $finder->getDescription(),
          $finder->getStatusText(),
          theme('links__ctools_dropbutton', array(
            'links' => $finder->getActions(),
            'attributes' => array('class' => array('links', 'inline'))
          )),
        ),
        'no_striping' => TRUE,
      );
      if ($finder->hasRun()) {
        $countRun++;
      }
    }

    // Add a run/refresh all button.
    if ($countRun === 0) {
      $title = t('Run all');
    }
    elseif ($countRun === count($instanceKeys)) {
      $title = t('Refresh all');
    }
    else {
      $title = t('Run/refresh all');
    }
    $rows[] = array(
      'data' => array(
        $title,
        t("Note: you won't be able to override the global settings per finder."),
        theme('links__ctools_dropbutton', array(
          'links' => array( 'run'  => array(
            'title' => $title,
            'href' => 'admin/config/media/unused-images',
            'query' => array('all' => $variable),
          )),
          'attributes' => array('class' => array('links', 'inline'))
        )),
      ),
      'no_striping' => TRUE,
    );
  }

  return $rows;
}

/**
 * Form callback for the 'admin/config/media/unused-images/settings' page.
 *
 * @param array $form
 *   The form.
 *
 * @return array
 *   The form.
 */
function unused_images_settings(array $form) {
  $form['unused_images_extensions'] = array(
    '#type' => 'textfield',
    '#title' => t('File extensions'),
    '#default_value' => implode(',', variable_get('unused_images_extensions', array())),
    '#required' => TRUE,
    '#description' => t('A comma separated list of file extensions to restrict the search to.'),
  );

  // Fields related to finding images.
  $form['image_finding'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#tree' => FALSE,
    '#title' => t('Settings related to finding images'),
  );

  $options = array();
  foreach (file_get_stream_wrappers() as $scheme => $wrapperInfo) {
    $finder = new UnusedImagesImageFinder($scheme);
    $options[$finder->getInstanceKey()] = $finder->getDescription();
  }
  $form['image_finding']['unused_images_paths'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Image finders'),
    '#description' => t('Check all places where you want to look for image files.'),
    '#default_value' => variable_get('unused_images_paths', array()),
    '#options' => $options,
  );

  $form['image_finding']['unused_images_exclude_folders'] = array(
    '#type' => 'textarea',
    '#title' => t('Sub folders to exclude'),
    '#default_value' => implode("\n", variable_get('unused_images_exclude_folders', array())),
    '#description' => t('A default list of sub folders to exclude, one per line. This list may be overridden per search path. The styles sub folder should be excluded when searching for images to prevent them from being removed. Adding a sub folder of which you know that it does not contain images just speeds up the process. Sub folders that contain images that are referenced externally and thus not on the Drupal part of your website, should be excluded as well.'),
  );

  // Fields related to finding usages.
  $form['usage_finding'] = array(
    '#type' => 'fieldset',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    '#tree' => FALSE,
    '#title' => t('Settings related to finding usages of images'),
  );

  $options = array();
  $finders = unused_images_get_usage_finders();
  foreach ($finders as $key => $finderInfo) {
    $finder = unused_images_get_action_instance($key, FALSE);
    $options[$key] = $finder->getDescription();
  }
  $form['usage_finding']['unused_images_usages'] = array(
    '#type' => 'checkboxes',
    '#title' => t('Usage finders'),
    '#description' => t('Check all places where you want to look for usages of the found images. The Locales tables are used by several i18n modules, like i18n_blocks, and are thus more important to check on a multi lingual site. Unchecking an item may speed up the process but may lead to missing usages, so only do so if you are sure no images are used in that place.'),
    '#default_value' => variable_get('unused_images_usages', array_keys($options)),
    '#options' => $options,
  );

  $form['usage_finding']['unused_images_revisions'] = array(
    '#type' => 'checkbox',
    '#title' => t('Check revisions'),
    '#description' => t('Indicate whether to also check revisions of the selected fields. Not checking this may (considerably) speed up the process but may lead to missing usages in older versions of your content. You can override this value per field when actually running the usage finders.'),
    '#default_value' => variable_get('unused_images_revisions', 1),
  );

  $baseUrls = variable_get('unused_images_base_urls', null);
  // As long as no value is set for this setting, we use the similar Pathologic
  // setting as default.
  global $base_url;
  if ($baseUrls === NULL && module_exists('pathologic')) {
    $baseUrls = variable_get('pathologic_local_paths', '');
    $baseUrls = array_filter(array_map('trim', explode("\n", $baseUrls)));
    $baseUrls = array_unique(array_merge(array($base_url), $baseUrls));
  }
  if (empty($baseUrls)) {
    $baseUrls = array($base_url);
  }
  /** @noinspection HtmlUnknownTarget */
  $form['usage_finding']['unused_images_base_urls'] = array(
    '#type' => 'textarea',
    '#title' => t('All base URLs for this site'),
    '#default_value' => implode("\n", $baseUrls),
    '#description' => t('If this site is or was available at more than one base URL, enter them here, separated by line breaks.')
      . ' ' . t('This setting is based on a similar setting from the <a href="@url">Pathologic module</a> but it expects protocol relative URLs ending with a /, not just base paths.', array(
        '@url' => url('https://www.drupal.org/project/pathologic'),
      ))
      . ' ' . t('As the default value may come from the Pathologic module, please correct the lines to contain only base URLs in the form of //www.example.com/my/base/path/.'),
    '#element_validate' => array('unused_images_settings_form_validate_base_urls'),
  );

  // Make it a system settings form, but with our own interception of the submit
  // handler.
  $form = system_settings_form($form);
  foreach ($form['#submit'] as &$handler) {
    if ($handler === 'system_settings_form_submit') {
      $handler = 'unused_images_settings_form_submit';
    }
  }

  return $form;
}

/**
 * Validate handler for the base URLs setting.
 *
 * @param array $element
 * @param array $form_state
 */
function unused_images_settings_form_validate_base_urls(array &$element, array &$form_state) {
  $value = $element['#value'];
  if (!empty($value)) {
    $baseUrls = array_filter(array_map('trim', explode("\n", $value)));
    // Add ending / if necessary and
    $baseUrls = array_map(function($url) {
      if (mb_substr($url, -1) !== '/') {
        $url .= '/';
      }
      return $url;
    }, $baseUrls);
    $element['#value'] = implode("\n", $baseUrls);
    form_set_value($element, $element['#value'], $form_state);
    $line = 1;
    $message = array();
    foreach ($baseUrls as $baseUrl) {
      if (preg_match('|^//[^./]+(\.[^./]+)*/([^/]*/)*$|', $baseUrl) !== 1) {
        $message[] = t('Line @line: %path is not a correct base URL.', array(
          '@line' => $line,
          '%path' => $baseUrl,
        ));
      }
      $line++;
    }
    if (!empty($message)) {
      $message[] = t('Base URLs should have a format like: //www.example.com/my/base/path/');
      if (count($message) > 2) {
        $message = '<ul><li>' . implode('</li><li>', $message) . '</li></ul>';
      }
      else {
        $message = implode(' ', $message);
      }
      form_error($element, $message);
    }
  }
}

/*
 * Submit handler for the 'unused_images_settings' form.
 *
 * @param array $form
 * @param array $form_state
 */
function unused_images_settings_form_submit(/** @noinspection PhpUnusedParameterInspection */ array $form, array &$form_state) {
  // Convert textual lists to arrays before storing them in variable.
  variable_set('unused_images_paths', array_keys(array_filter($form_state['values']['unused_images_paths'])));
  if (isset($form_state['values']['unused_images_extensions'])) {
    variable_set('unused_images_extensions', explode(',', strtolower($form_state['values']['unused_images_extensions'])));
  }
  if (isset($form_state['values']['unused_images_exclude_folders'])) {
    $excludeFolders = array_filter(array_map('trim', explode("\n", $form_state['values']['unused_images_exclude_folders'])));
    variable_set('unused_images_exclude_folders', $excludeFolders);
  }
  variable_set('unused_images_usages', array_keys(array_filter($form_state['values']['unused_images_usages'])));
  variable_set('unused_images_revisions', $form_state['values']['unused_images_revisions']);
  if (isset($form_state['values']['unused_images_base_urls'])) {
    $baseUrls = array_filter(array_map('trim', explode("\n", $form_state['values']['unused_images_base_urls'])));
    variable_set('unused_images_base_urls', $baseUrls);
  }

  drupal_set_message(t('The configuration options have been saved.'));
}

/**
 * Form callback for the 'admin/config/media/unused-images/run' page.
 *
 * @param array $form
 *   The form.
 * @param array $form_state
 *
 * @return array
 *   The form.
 */
function unused_images_run(array $form, array &$form_state) {
  $instance = unused_images_get_action_instance();
  $form = $instance->fields($form, $form_state);
  $form['#submit'][] = 'unused_images_run_submit';
  return $form;
}

/**
 * Submit handler for the 'unused_images_find_images' form.
 *
 * @param array $form
 * @param array $form_state
 */
function unused_images_run_submit(array $form, array &$form_state) {
  $instance = unused_images_get_action_instance();
  $instance->submit($form, $form_state);
}

/**
 * Page callback for the 'admin/config/media/unused-images/delete' page.
 */
function unused_images_delete_results() {
  $instance = unused_images_get_action_instance();
  $instance->deleteResult();
  drupal_goto('admin/config/media/unused-images/status');
}

/**
 * Page callback for the 'admin/config/media/unused-images/move' page.
 *
 * @param array $form
 *   The form.
 * @param array $form_state
 *
 * @return array
 *   The form.
 */
function unused_images_move(array $form, array &$form_state) {
  $instance = unused_images_get_action_instance();
  $form = $instance->fields($form, $form_state);
  $form['#submit'][] = 'unused_images_run_submit';
  return $form;
}

/**
 * Returns an instance of an UnusedImagesActionBase class
 *
 * @param string $key
 *   A key that will be parsed to find out what sub class to instantiate. If not
 *   given, $_REQUEST['instance'] will be used.
 * @param bool $restrictSelected
 *   Pass true to restrict the instance to one of those selected on the settings
 *   page.
 *
 * @return \UnusedImagesActionBase|null
 *   An instance of an action form class. The request will have been terminated
 *   with a 404 if $key is not well formed.
 */
function unused_images_get_action_instance($key = NULL, $restrictSelected = TRUE) {
  if (empty($key) && isset($_REQUEST['instance'])) {
    $key = $_REQUEST['instance'];
  }
  $instance = NULL;
  $class = NULL;
  $arguments = array();

  if (!empty($key)) {
    $parts = explode('|', $key);
    if (count($parts) >= 2 && count($parts) <= 3 && $parts[0] === 'unused_images') {
      $className = $parts[1];
      $argument = count($parts) === 3 ? $parts[2] : NULL;
      if (class_exists($className) && is_subclass_of($className, UnusedImagesActionBase::class)) {
        if (is_subclass_of($className, UnusedImagesDifferencesBase::class)) {
          if ($argument === NULL) {
            $class = $className;
          }
        }
        elseif ($className === UnusedImagesImageFinder::class) {
          if (!$restrictSelected || in_array($key, variable_get('unused_images_paths', array()))) {
            $class = $className;
            $arguments[] = $argument;
            $arguments[] = variable_get('unused_images_extensions', array());
          }
        }
        elseif (is_subclass_of($className, UnusedImagesUsageFinderBase::class)) {
          if (!$restrictSelected || in_array($key, variable_get('unused_images_usages', array()))) {
            $class = $className;
            if ($argument !== NULL) {
              $arguments[] = $argument;
            }
            $arguments[] = variable_get('unused_images_extensions', array());
          }
        }
      }
    }
  }

  if (!empty($class)) {
    try {
      $reflector = new ReflectionClass($class);
      /** @var \UnusedImagesActionBase $instance */
      $instance = $reflector->newInstanceArgs($arguments);
    } catch (ReflectionException $e) {
      $instance = NULL;
    }
  }
  else {
    drupal_not_found();
  }
  return $instance;
}
