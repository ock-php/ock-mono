<?php

namespace Drupal\Tests\themekit\Kernel;

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Markup;
use Drupal\KernelTests\KernelTestBase;

/**
 * @see \Drupal\KernelTests\Core\Render\Element\RenderElementTypesTest
 */
class KernelTest_ThemekitElementTypes extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = ['themekit'];

  protected function setUp_() {
    parent::setUp();
    $this->installConfig(['system']);
    \Drupal::service('router.builder')->rebuild();
  }

  public function testThemekitContainer() {

    // Basic container with no attributes.
    $this->assertElements(
      "<div>foo</div>\n",
      [
        /* @see theme_themekit_container() */
        '#type' => 'themekit_container',
        '#markup' => 'foo',
      ],
      "#type 'themekit_container' with no HTML attributes");

    // Container with a class.
    $this->assertElements(
      '<div class="bar">foo</div>' . "\n",
      [
        '#type' => 'themekit_container',
        '#markup' => 'foo',
        '#attributes' => ['class' => ['bar']],
      ],
      "#type 'themekit_container' with a class HTML attribute");

    // Container with children.
    $this->assertElements(
      "<div>foo</div>\n",
      [
        '#type' => 'themekit_container',
        'child' => ['#markup' => 'foo'],
      ],
      "#type 'themekit_container' with child elements");

    // Container with children.
    $this->assertElements(
      "<article>foo</article>\n",
      [
        '#type' => 'themekit_container',
        '#tag_name' => 'article',
        'child' => ['#markup' => 'foo'],
      ],
      "#type 'themekit_container' with tag name");
  }

  public function testThemekitItemContainers() {

    $this->assertElements(
      ''
      . '<div class="field-item">X</div>'
      . '<div class="field-item">Y</div>'
      . '<div class="field-item">Z</div>'
      . '',
      [
        /* @see theme_themekit_item_containers() */
        '#theme' => 'themekit_item_containers',
        '#item_attributes' => ['class' => ['field-item']],
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
        ['#markup' => 'Z'],
      ],
      "#theme 'themekit_item_containers' with three items.");

    $this->assertElements(
      'XYZ',
      [
        /* @see theme_themekit_item_containers() */
        '#theme' => 'themekit_item_containers',
        '#item_attributes' => ['class' => ['field-item']],
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
        ['#markup' => 'Z'],
        '#item_tag_name' => FALSE,
      ],
      "#theme 'themekit_item_containers' with #item_tag_name false.");

    $this->assertElements(
      ''
      . '<ul class="menu">'
      . '<li>X</li>'
      . '<li>Y</li>'
      . '<li>Z</li>'
      . '</ul>'
      . "\n",
      [
        // Outer wrapper <ol>.
        '#type' => 'themekit_container',
        '#tag_name' => 'ul',
        '#attributes' => ['class' => ['menu']],
        // Wrap each item in <li>.
        '#theme' => 'themekit_item_containers',
        '#item_tag_name' => 'li',
        // Items.
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
        ['#markup' => 'Z'],
      ],
      "#theme 'themekit_item_containers' with #type 'themekit_container'.");
  }

  public function testThemekitItemList() {

    $this->assertElements(
      ''
      . '<ul class="menu">'
      . '<li>X</li>'
      . '<li>Y</li>'
      . '<li><div>Z</div>' . "\n" . '</li>'
      . '</ul>'
      . "\n",
      [
        /* @see theme_themekit_list() */
        '#theme' => 'themekit_list',
        '#attributes' => ['class' => ['menu']],
        // Items.
        'x' => ['#markup' => 'X'],
        ['#markup' => 'Y'],
        [
          '#type' => 'themekit_container',
          ['#markup' => 'Z'],
        ],
      ],
      "#theme 'themekit_list'.");

    $this->assertElements(
      ''
      . '<ol>'
      . '<li>X</li>'
      . '<li>Y</li>'
      . '</ol>'
      . "\n",
      [
        /* @see theme_themekit_list() */
        '#theme' => 'themekit_list',
        '#tag_name' => 'ol',
        // Items.
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
      ],
      "#theme 'themekit_list' with #tag_name 'ol'.");
  }

  public function testThemekitSeparatorList() {

    $this->assertElements(
      'X|Y|<span>Z</span>' . "\n",
      [
        /* @see theme_themekit_separator_list() */
        '#theme' => 'themekit_separator_list',
        '#separator' => '|',
        // Items.
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
        [
          '#type' => 'themekit_container',
          '#tag_name' => 'span',
          ['#markup' => 'Z']
        ]
      ],
      "#theme 'themekit_separator_list' with #separator '|'.");

    $this->assertElements(
      'X<hr/>Y<hr/><span>Z</span>' . "\n",
      [
        /* @see theme_themekit_separator_list() */
        '#theme' => 'themekit_separator_list',
        '#separator' => Markup::create('<hr/>'),
        // Items.
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
        [
          '#type' => 'themekit_container',
          '#tag_name' => 'span',
          ['#markup' => 'Z']
        ]
      ],
      "#theme 'themekit_separator_list' with #separator HR tag.");
  }

  /**
   * Asserts that an array of elements is rendered properly.
   *
   * @param string $expected_html
   *   The expected markup.
   * @param array $elements
   *   The render element array to test.
   * @param string $message
   *   Assertion message.
   */
  private function assertElements($expected_html, array $elements, $message) {
    $actual_html = (string) \Drupal::service('renderer')->renderRoot($elements);

    $out = '<table><tr>';
    $out .= '<td valign="top"><pre>' . Html::escape($expected_html) . '</pre></td>';
    $out .= '<td valign="top"><pre>' . Html::escape($actual_html) . '</pre></td>';
    $out .= '</tr></table>';
    $this->verbose($out);

    $this->assertSame(
      $expected_html,
      $actual_html,
      Html::escape($message));
  }
}
