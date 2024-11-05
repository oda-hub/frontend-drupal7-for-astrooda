<?php

namespace Drupal\Tests\ckeditor_colorbox_inline\Functional;

use Drupal\filter\Entity\FilterFormat;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\node\Traits\ContentTypeCreationTrait;
use Drupal\Tests\node\Traits\NodeCreationTrait;

/**
 * Tests for the ckeditor_colorbox_inline module.
 *
 * @group ckeditor_colorbox_inline
 */
class CkeditorColorboxInlineFilterTest extends WebDriverTestBase {

  use NodeCreationTrait;
  use ContentTypeCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'ckeditor_colorbox_inline',
    'colorbox_library_test',
    'node',
    'text',
  ];

  /**
   * How long to wait for colorbox to launch.
   */
  public const COLORBOX_WAIT_TIMEOUT = 500;

  /**
   * Test Node.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->createContentType(['type' => 'page']);

    FilterFormat::create([
      'format' => 'full_html',
      'name' => 'Full HTML',
      'filters' => [
        'ckeditor_colorbox_inline' => [
          'status' => 1,
          'css_classes' => 'colorbox',
        ],
      ],
    ])->save();
  }

  /**
   * Test the inline colorbox launches when a link is clicked.
   */
  public function testInlineColorbox(): void {
    $this->node = $this->createNode([
      'body' => [
        'value' => '<img src="https://www.drupal.org/files/cta/graphic/Association_Supporting_Partner_Badge_3.png" alt="Supporting Partner Badge">',
        'format' => 'full_html',
      ],
    ]);

    $this->drupalGet('node/' . $this->node->id());
    $this->click('a.colorbox');
    $this->getSession()->wait(static::COLORBOX_WAIT_TIMEOUT);
    $this->assertSession()->elementsCount('css', '#colorbox img.cboxPhoto', 1);
  }

  /**
   * Test the skip inline colorbox.
   */
  public function testInlineSkipColorbox(): void {
    $this->node = $this->createNode([
      'body' => [
        'value' => '<img class="noColorbox" src="https://www.drupal.org/files/cta/graphic/Association_Supporting_Partner_Badge_3.png" alt="Supporting Partner Badge">',
        'format' => 'full_html',
      ],
    ]);

    $this->drupalGet('node/' . $this->node->id());
    $this->assertSession()->elementsCount('css', 'a.colorbox', 0);
    $this->assertSession()->elementsCount('css', '#colorbox img.cboxPhoto', 0);
  }

}
