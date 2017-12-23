<?php

namespace Drupal\renderkit8\ListFormat;

use Drupal\renderkit8\Schema\CfSchema_ListFormat_Expert;

class ListFormat_ElementDefaults implements ListFormatInterface {

  /**
   * @var array
   */
  private $elementDefaults;

  /**
   * @CfrPlugin("expert", "Expert")
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public static function createExpertSchema() {
    return new CfSchema_ListFormat_Expert();
  }

  /**
   * @param array $elementDefaults
   */
  public function __construct(array $elementDefaults) {
    $this->elementDefaults = $elementDefaults;
  }

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  public function buildList(array $builds) {
    return $builds + $this->elementDefaults;
  }
}
