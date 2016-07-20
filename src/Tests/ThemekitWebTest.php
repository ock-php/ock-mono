<?php

namespace Drupal\themekit\Tests;

class ThemekitWebTest extends \DrupalWebTestCase {

  function setUp() {
    parent::setUp('themekit');
  }

  public static function getInfo() {
    // Note: getInfo() strings should not be translated.
    return array(
      'name' => 'Themekit web test',
      'description' => 'Tests theme functions provided by themekit.',
      'group' => 'Themekit',
    );
  }

  public function testThemekitItemContainers() {

    $element = [
      /* @see theme_themekit_item_containers() */
      '#theme' => 'themekit_item_containers',
      '#item_tag_name' => 'div',
      '#item_attributes' => ['class' => ['field-item']],
      '#first' => 'field-item-first',
      '#last' => 'field-item-last',
      '#zebra' => ['field-item-even', 'field-item-odd'],
      ['#markup' => 'X'],
      ['#markup' => 'Y'],
      ['#markup' => 'Z'],
    ];

    $html_expected = ''
      . '<div class="field-item field-item-even field-item-first">X</div>'
      . '<div class="field-item field-item-odd">Y</div>'
      . '<div class="field-item field-item-even field-item-last">Z</div>'
      . '';

    $this->assertIdentical($html_expected, theme('themekit_item_containers', ['element' => $element]));

    $this->assertIdentical($html_expected, drupal_render($element));
  }

  public function testThemekitItemContainersWithContainer() {

    $element = [
      // Outer wrapper <ol>.
      '#type' => 'themekit_container',
      '#tag_name' => 'ul',
      '#attributes' => ['class' => ['menu']],
      // Wrap each item in <li>.
      '#theme' => 'themekit_item_containers',
      '#item_tag_name' => 'li',
      '#zebra' => TRUE,
      // Items.
      ['#markup' => 'X'],
      ['#markup' => 'Y'],
      ['#markup' => 'Z'],
    ];

    $html_expected = ''
      . '<ul class="menu">'
      . '<li class="even">X</li>'
      . '<li class="odd">Y</li>'
      . '<li class="even">Z</li>'
      . '</ul>'
      . '';

    $this->assertIdentical($html_expected, $html = drupal_render($element));
  }

}
