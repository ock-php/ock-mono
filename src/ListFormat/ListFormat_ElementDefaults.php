<?php

namespace Drupal\renderkit\ListFormat;

class ListFormat_ElementDefaults implements ListFormatInterface {

  /**
   * @var array
   */
  private $elementDefaults;

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
  function buildList(array $builds) {
    return $builds + $this->elementDefaults;
  }
}
