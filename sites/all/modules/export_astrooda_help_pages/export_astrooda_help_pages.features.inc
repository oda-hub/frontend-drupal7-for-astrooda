<?php
/**
 * @file
 * export_astrooda_help_pages.features.inc
 */

/**
 * Implements hook_node_info().
 */
function export_astrooda_help_pages_node_info() {
  $items = array(
    'astrooda_help' => array(
      'name' => t('Astrooda Help'),
      'base' => 'node_content',
      'description' => t('A dedicated content type for the help pages of the module Astrooda.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
