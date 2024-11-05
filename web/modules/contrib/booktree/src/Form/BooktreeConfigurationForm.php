<?php

namespace Drupal\booktree\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class BooktreeConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'booktree_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'booktree.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('booktree.settings');
    $form = array();
    $form['booktree_start'] = array(
      '#type' => 'textfield',
      '#title' => t('Root Node ID'),
      '#required' => TRUE,
      '#default_value' => $config->get('booktree_start'),
      '#description' => t('Start point of the tree (the root).')
    );

    $form['booktree_deep'] = array(
      '#type' => 'textfield',
      '#title' => t('Deep Max'),
      '#required' => TRUE,
      '#default_value' => $config->get('booktree_deep'),
      '#description' => t('Max deep of the tree with the root.')

    );
    $form['booktree_trim'] = array(
      '#type' => 'textfield',
      '#title' => t('Trimmer'),
      '#required' => TRUE,
      '#default_value' => $config->get('booktree_trim'),
      '#description' => t('Max lenght of title.')

    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('booktree.settings')
      ->set('booktree_start', $form_state->getValue('booktree_start'))
      ->set('booktree_deep', $form_state->getValue('booktree_deep'))
      ->set('booktree_trim', $form_state->getValue('booktree_trim'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
