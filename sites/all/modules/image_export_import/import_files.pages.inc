<?php

/**
 * @file
 * To change this license header, choose License Headers in Project Properties.
 */

/**
 * {@inheritdoc}
 */
function image_export_import_bulk_import_images_form($form, &$form_state) {
  // Choose action type.
  $form['import_type'] = [
    '#title' => 'Action Type',
    '#type' => 'select',
    '#weight' => 1,
    '#description' => t('Please choose your action like: export, import and delete unused files from CMS.'),
    '#options' => [
      'export' => 'Export',
      'import' => 'Import',
    ],
  ];
  // Upload csv file.
  $form['importimage_csv'] = [
    '#type' => 'managed_file',
    '#title' => 'Choose CSV File',
    '#description' => t('Please upload csv file to create.'),
    '#upload_location' => 'public://imported-images/',
    '#weight' => 2,
    '#upload_validators' => [
      'file_validate_extensions' => ['csv'],
    ],
    '#states' => [
      'visible' => [':input[name="import_type"]' => ['value' => 'import']],
    ],
  ];
  $form['upload_zip'] = [
    '#title' => t('Upload image zip file'),
    '#type' => 'managed_file',
    '#weight' => 2.5,
    '#upload_location' => 'public://migrate_images/',
    '#upload_validators' => [
      'file_validate_extensions' => ['zip tar tar.gz'],
    ],
    '#description' => t('Create package of media files and upload to Drupal. Valid extensions are .zip, .tar.gz, .tar. All files will be extracted to public://migrate_images/'),
    '#states' => [
      'visible' => [':input[name="import_type"]' => ['value' => 'import']],
    ],
  ];
  // Get content types from CMS.
  $content_types = node_type_get_names();
  $selected = !empty($form_state['values']['content_types']) ? $form_state['values']['content_types'] : key($content_types);
  $form['content_types'] = [
    '#title' => 'Content types',
    '#type' => 'select',
    '#required' => TRUE,
    '#weight' => 3,
    '#default_value' => $selected,
    '#description' => t('Please select content type for import.'),
    '#options' => $content_types,
    '#states' => [
      'invisible' => [':input[name="import_type"]' => ['value' => 'delete']],
    ],
    '#ajax' => [
      'callback' => 'image_export_import_dependent_dropdown_callback',
      'wrapper' => 'dropdown-second-replace',
    ],
  ];
  // Get content types image fields.
  $form['image_fields'] = [
    '#title' => $content_types[$selected] . ' Image field',
    '#type' => 'select',
    '#options' => image_export_import_get_all_image_fields('node', $selected),
    '#default_value' => $selected,
    '#weight' => 4,
    '#description' => t('Please select image field from  content type.'),
    '#prefix' => '<div id="dropdown-second-replace">',
    '#suffix' => '</div>',
    '#states' => [
      'invisible' => [':input[name="import_type"]' => ['value' => 'delete']],
    ],
  ];
  // Export body summary.
  $form['export_body'] = [
    '#type' => 'checkbox',
    '#weight' => 6,
    '#description' => t('Include Body and summary as well (In case of import action Body, Summary will update).'),
    '#title' => "Body and Summary.",
    '#states' => [
      'invisible' => [':input[name="import_type"]' => ['value' => 'delete']],
    ],
  ];

  // Create node based on title.
  $form['new_node'] = [
    '#type' => 'checkbox',
    '#weight' => 7,
    '#description' => t('If nid not present in csv then create new node with title, summary,body and images fields.'),
    '#title' => "Create new node",
    '#states' => [
      'visible' => [':input[name="import_type"]' => ['value' => 'import']],
    ],
  ];

  // Import csv button.
  $form['import']['#type'] = 'actions';
  $form['import']['submit'] = [
    '#type' => 'submit',
    '#value' => t('Submit'),
    '#attributes' => array('class' => array('form-submit')),
  ];

  return $form;
}

/**
 * {@inheritdoc}
 */
function image_export_import_dependent_dropdown_callback(&$form, $form_state) {
  return $form['image_fields'];
}

/**
 * Returns image fields based on content type.
 *
 * @param mixed $entity_type
 *   Content type name.
 * @param mixed $content_type
 *   This will contains nid.
 */
function image_export_import_get_all_image_fields($entity_type, $content_type) {
  $image_field = array();
  $fields = field_info_instances($entity_type, $content_type);
  foreach ($fields as $value) {
    if ($value['widget']['type'] == 'image_image') {
      $image_field[$value['field_name']] = $value['label'];
    }
  }
  return $image_field;
}

/**
 * {@inheritdoc}
 */
function image_export_import_bulk_import_images_form_validate($form, $form_state) {
  // Delete confirmation validation.
  $import_type = $form_state['values']['import_type'];
  // Import csv field validation.
  $importimage_csv = $form_state['values']['importimage_csv'];
  if (empty($importimage_csv) && $import_type == 'import') {
    form_set_error('importimage_csv', t('Please upload csv file.'));
  }
  // Import zip file field validation.
  $upload_zip = $form_state['values']['upload_zip'];
  if (empty($upload_zip) && $import_type == 'import') {
    form_set_error('upload_zip', t('Please upload zip file.'));
  }
}

/**
 * {@inheritdoc}
 */
function image_export_import_bulk_import_images_form_submit($form, $form_state) {
  // Checked logged in admin user.
  global $user;
  $current = $user->uid;
  $import_type = $form_state['values']['import_type'];
  $content_types = $form_state['values']['content_types'];
  $image_fields = $form_state['values']['image_fields'];
  $export_body = $form_state['values']['export_body'];
  switch ($import_type) {
    case "export":
      // Create filename based on your select export option.
      $filename = (!empty($export_body)) ? $content_types . '_' . $image_fields . '_body_' . $current . '.csv' : $content_types . '_' . $image_fields . '_' . $current . '.csv';

      $path = image_export_import_create_csvfile_export_data($filename, $content_types, $image_fields, $export_body);
      // Check file and export as csv format.
      if (is_file($path)) {
        drupal_add_http_header('Content-disposition', 'attachment; filename=' . $filename);
        readfile($path);
        exit;
      }
      break;

    case "import":
      // Get fields value from $form_state.
      $new_node = $form_state['values']['new_node'];
      $result = 0;
      // Get Zip file and extract to detination directory.
      $upload_zip = $form_state['values']['upload_zip'];
      $upload_zip_file = file_load($upload_zip);
      // Load the appropriate archiver and extarct the archive.
      $zip_obj = file_stream_wrapper_get_instance_by_uri($upload_zip_file->uri);
      $archiver = image_export_import_get_archiver($zip_obj->realpath());
      $result_data = $archiver->extractTo('public://migrate_images/');
      // If zip succcessfullt extracted.
      if ($result_data === TRUE) {
        $csv_file = $form_state['values']['importimage_csv'];
        $file = file_load($csv_file);
        $csv_obj = file_stream_wrapper_get_instance_by_uri($file->uri);
        $handle = fopen($csv_obj->realpath(), "r");
        // Get fieldname from csv, we assume its first row.
        fgetcsv($handle, 0, ',', '"');
        $operations = [];
        while (($data = fgetcsv($handle, 0, ',', '"')) !== FALSE) {
          $image_data = [
            'data' => $data,
            'content_type' => $content_types,
            'new_node' => $new_node,
            'image_field' => $image_fields,
            'save_body' => $export_body,
            'result' => ++$result,
          ];
          $operations[] = [
            'image_export_import_image_from_csv',
            [$image_data],
          ];
        }
        // Execute batch oprations.
        image_export_import_batch_oprations($operations, $handle);
        // Delete the archive file that was uploaded.
        file_delete($upload_zip_file);
        file_delete($file);
      }
      else {
        drupal_set_message(t('There is some problem related to extraction
          of the file. Please upload and try again.'), 'error', FALSE);
      }
      break;
  }
}

/**
 * Write data in csv file to export functionality.
 *
 * @param mixed $filename
 *   Name of csv file.
 * @param mixed $content_types
 *   Content type name.
 * @param mixed $image_fields
 *   Image field name.
 * @param mixed $export_body
 *   Check user want to export body summary or not.
 *
 * @return mixed
 *   Return csv file url.
 */
function image_export_import_create_csvfile_export_data($filename, $content_types, $image_fields, $export_body) {
  // Get file path in CMS.
  $path_obj = file_stream_wrapper_get_instance_by_uri("public://$filename");
  $path = $path_obj->realpath();
  $file = fopen($path, 'w');
  // Send the column headers.
  if (!empty($export_body)) {
    fputcsv($file, [
      'Nid',
      "Node_" . $content_types . '_Title',
      $image_fields, 'IMG_Alt',
      'IMG_title',
      'Node_Summary',
      'Node_Description',
    ]);
  }
  else {
    fputcsv($file, [
      'Nid',
      "Node_" . $content_types . '_Title',
      $image_fields,
      'IMG_Alt',
      'IMG_title',
    ]);
  }
  // Create migrate_images if not exists.
  $mgi_obj = file_stream_wrapper_get_instance_by_uri("public://migrate_images");
  if (!is_dir($mgi_obj->realpath())) {
    mkdir($mgi_obj->realpath(), 0777, TRUE);
    chmod($mgi_obj->realpath(), 0777);
  }
  // Sample data. This can be fetched from mysql too.
  $query = new EntityFieldQuery();
  $result = $query->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', $content_types)
    ->execute();
  $nodes = node_load_multiple(array_keys($result['node']));
  foreach ($nodes as $node) {
    $row = $img_title = $alt = $basename = [];
    $row[0] = $node->nid;
    $row[1] = $node->title;
    // Check image exits or not.
    if (!empty($node->{$image_fields}[LANGUAGE_NONE])) {
      $total_images = count($node->{$image_fields}[LANGUAGE_NONE]);
      // Check multiple image value for this node.
      if ($total_images >= 1) {
        $image_index = 1;
        foreach ($node->{$image_fields}[LANGUAGE_NONE] as $image_val) {
          if (!empty($image_val['fid'])) {
            $basename[$image_index] = basename($image_val['uri']);
            // Copy files under migrate_images directory.
            $src_obj = file_stream_wrapper_get_instance_by_uri($image_val['uri']);
            $src = $src_obj->realpath();
            $dest_obj = file_stream_wrapper_get_instance_by_uri("public://migrate_images");
            $dest = $dest_obj->realpath();
            shell_exec("cp -r $src $dest");
          }
          $alt[$image_index] = ($image_val['alt']) ? $image_val['alt'] : '';
          $img_title[$image_index] = ($image_val['title']) ? $image_val['title'] : '';
          $image_index++;
        }
        // Concate image values for multiple fields.
        $row[2] = implode("|", $basename);
        $row[3] = implode("|", $alt);
        $row[4] = implode("|", $img_title);
      }
    }
    if (!empty($export_body)) {
      $row[5] = ($node->body[LANGUAGE_NONE][0]['summary']) ? $node->body[LANGUAGE_NONE][0]['summary'] : '';
      $row[6] = ($node->body[LANGUAGE_NONE][0]['value']) ? $node->body[LANGUAGE_NONE][0]['value'] : '';
    }
    // Write each record in csv file.
    fputcsv($file, $row);
  }

  fclose($file);
  return $path;
}

/**
 * Get an appropriate archiver class for the file.
 *
 * @param string $file
 *   The file path.
 */
function image_export_import_get_archiver($file) {
  $extension = strstr(pathinfo($file)['basename'], '.');
  switch ($extension) {
    case '.tar.gz':
    case '.tar':
      $extract = new \PharData($file);
      break;

    case '.zip':
      $extract = new \ZipArchive($file);
      $extract->open($file);
    default:
      break;
  }
  return $extract;
}

/**
 * Batch operation execution.
 *
 * @param mixed $operations
 *   Object contains all information related to batch.
 * @param mixed $handle
 *   Manage batch operation.
 */
function image_export_import_batch_oprations($operations, $handle) {
  if (count($operations)) {
    // Once everything is gathered and ready to be processed.
    $batch = [
      'title' => t('Importing CSV...'),
      'operations' => $operations,
      'finished' => 'image_export_import_csv_finished_callback',
      'error_message' => t('The import has encountered an error.'),
      'progress_message' => t('Imported @current of @total rows.'),
    ];
    batch_set($batch);
    fclose($handle);
  }
}
