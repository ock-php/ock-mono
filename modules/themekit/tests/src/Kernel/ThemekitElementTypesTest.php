<?php
declare(strict_types=1);

namespace Drupal\Tests\themekit\Kernel;

use Drupal\Component\Utility\Html;
use Drupal\Core\Render\Markup;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Url;
use Drupal\KernelTests\KernelTestBase;
use Drupal\themekit\Element\RenderElement_ThemekitContainer;
use Ock\Testing\ExceptionSerializationTrait;

/**
 * @see \Drupal\KernelTests\Core\Render\Element\RenderElementTypesTest
 */
class ThemekitElementTypesTest extends KernelTestBase {

  use ExceptionSerializationTrait;

  /**
   * Modules to enable.
   *
   * @var list<string>
   */
  protected static $modules = ['themekit', 'system'];

  public function testThemekitContainer(): void {
    // Basic container with no attributes.
    $this->assertElements(
      "<div>foo</div>\n",
      [
        '#type' => RenderElement_ThemekitContainer::ID,
        '#markup' => 'foo',
      ],
      "#type 'themekit_container' with no HTML attributes",
    );

    // Container with a class.
    $this->assertElements(
      '<div class="bar">foo</div>' . "\n",
      [
        '#type' => RenderElement_ThemekitContainer::ID,
        '#markup' => 'foo',
        '#attributes' => ['class' => ['bar']],
      ],
      "#type 'themekit_container' with a class HTML attribute",
    );

    // Container with children.
    $this->assertElements(
      "<div>foo</div>\n",
      [
        '#type' => RenderElement_ThemekitContainer::ID,
        'child' => ['#markup' => 'foo'],
      ],
      "#type 'themekit_container' with child elements",
    );

    // Container with children.
    $this->assertElements(
      "<article>foo</article>\n",
      [
        '#type' => RenderElement_ThemekitContainer::ID,
        '#tag_name' => 'article',
        'child' => ['#markup' => 'foo'],
      ],
      "#type 'themekit_container' with tag name");

    // Container with children.
    $this->assertElements(
      "<div><div>foo</div>\n</div>\n",
      [
        '#type' => RenderElement_ThemekitContainer::ID,
        'child' => [
          '#type' => 'container',
          'child' => ['#children' => 'foo'],
        ],
      ],
      "#type 'themekit_container' with nested container",
    );
  }

  public function testThemekitLinkWrapper(): void {
    // Basic container with no attributes.
    $this->assertElements(
      '<a href="https://www.drupal.org"><div>foo</div>
</a>',
      [
        '#type' => 'themekit_link_wrapper',
        'content' => [
          '#type' => 'container',
          ['#children' => 'foo'],
        ],
        '#url' => Url::fromUri('https://www.drupal.org'),
      ],
      "#type 'themekit_link_wrapper' to https://www.drupal.org.",
    );
  }

  public function testThemekitItemContainers(): void {
    $this->assertElements(
      '<div class="field-item">X</div>'
      . '<div class="field-item">Y</div>'
      . '<div class="field-item">Z</div>',
      [
        /* @see theme_themekit_item_containers() */
        '#theme' => 'themekit_item_containers',
        '#item_attributes' => ['class' => ['field-item']],
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
        ['#markup' => 'Z'],
      ],
      "#theme 'themekit_item_containers' with three items.",
    );

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
      "#theme 'themekit_item_containers' with #item_tag_name false.",
    );

    $this->assertElements(
      '<ul class="menu">'
      . '<li>X</li>'
      . '<li>Y</li>'
      . '<li>Z</li>'
      . '</ul>'
      . "\n",
      [
        // Outer wrapper <ol>.
        '#type' => RenderElement_ThemekitContainer::ID,
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
      "#theme 'themekit_item_containers' with #type 'themekit_container'.",
    );
  }

  public function testThemekitItemList(): void {
    $this->assertElements(
      '<ul class="menu">'
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
          '#type' => RenderElement_ThemekitContainer::ID,
          ['#markup' => 'Z'],
        ],
      ],
      "#theme 'themekit_list'.",
    );

    $this->assertElements(
      '<ol>'
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

  public function testThemekitSeparatorList(): void {

    $this->assertElements(
      'X|Y|<span>Z</span>' . "\n",
      [
        '#theme' => 'themekit_separator_list',
        '#separator' => '|',
        // Items.
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
        [
          '#type' => RenderElement_ThemekitContainer::ID,
          '#tag_name' => 'span',
          ['#markup' => 'Z']
        ]
      ],
      "#theme 'themekit_separator_list' with #separator '|'.");

    $this->assertElements(
      'X<hr/>Y<hr/><span>Z</span>' . "\n",
      [
        '#theme' => 'themekit_separator_list',
        '#separator' => Markup::create('<hr/>'),
        // Items.
        // Empty item at the start is removed.
        ['#markup' => ''],
        ['#markup' => 'X'],
        ['#markup' => 'Y'],
        // Empty items with left-over whitespace are removed.
        ['#markup' => ' '],
        [
          '#type' => RenderElement_ThemekitContainer::ID,
          '#tag_name' => 'span',
          ['#markup' => 'Z']
        ],
        // Empty item at the end is removed.
        ['#markup' => ''],
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
  private function assertElements(string $expected_html, array $elements, string $message): void {
    $renderer = \Drupal::service('renderer');
    assert($renderer instanceof RendererInterface);
    $actual_html = (string) $renderer->renderRoot($elements);
    static::assertSame(
      $expected_html,
      $actual_html,
      Html::escape($message),
    );
  }

}
