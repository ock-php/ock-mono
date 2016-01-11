<?php

namespace Drupal\renderkit\ListFormat;

/**
 * List format that does not add any wrappers and containers.
 */
class ListFormat_Bare implements ListFormatInterface {

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  function buildList(array $builds) {
    return $builds;
  }
}
