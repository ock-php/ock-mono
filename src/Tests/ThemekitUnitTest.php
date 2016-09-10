<?php

namespace Drupal\themekit\Tests;

use Drupal\themekit\Callback\Callback_ElementReparent;

class ThemekitUnitTest extends \DrupalUnitTestCase {

  public function setUp() {
    parent::setUp();

    // Class loading does not work in unit tests, it seems.
    // See http://drupal.stackexchange.com/questions/49686/drupal-simpletest-class-autoloading-false
    require_once dirname(__DIR__) . '/Callback/Callback_ElementReparent.php';
  }

  public static function getInfo() {
    // Note: getInfo() strings should not be translated.
    return [
      'name' => 'Themekit unit test',
      'description' => 'Tests utility functions provided by themekit.',
      'group' => 'Themekit',
    ];
  }

  public function testCallbackElementReparent() {

    $element = [
      '#parents' => ['a', 'b', 'c'],
    ];

    $expected = [
      '#parents' => ['a', 'x', 'y'],
    ];

    $processor = new Callback_ElementReparent(2, ['x', 'y']);

    $this->assertIdentical($expected, $processor($element));
  }

}
