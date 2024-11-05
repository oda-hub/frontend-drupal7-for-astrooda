<?php

namespace Drupal\ckeditor_colorbox_inline\Plugin\Filter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\FormStateInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * Provides a 'CKEditor Colorbox Inline' filter.
 *
 * @Filter(
 *   id = "ckeditor_colorbox_inline",
 *   title = @Translation("Colorbox Inline Text Filter"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE,
 *   settings = {
 *     "css_classes" = "colorbox"
 *   },
 *   weight = -10
 * )
 */
class CkeditorColorboxInline extends FilterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['css_classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CSS classes'),
      '#default_value' => $this->settings['css_classes'],
      '#description' => $this->t('use space as delimiter'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    $dom = Html::load($text);

    $elements = $dom->getElementsByTagName('img');
    if ($elements->length === 0) {
      return new FilterProcessResult(Html::serialize($dom));
    }

    foreach ($elements as $element) {
      if (!$element->hasAttribute('class') || (\strpos($element->getAttribute('class'), 'noColorbox') === FALSE)) {
        $a = $dom->createElement('a');
        $element->parentNode->insertBefore($a, $element);
        $a->appendChild($element);
        $a->setAttribute('href', $element->getAttribute('src'));
        $a->setAttribute('class', $this->settings['css_classes']);
        $a->setAttribute('data-colorbox-gallery', 'ckeditor-colorbox-inline');
      }
    }

    return new FilterProcessResult(Html::serialize($dom));
  }

}
