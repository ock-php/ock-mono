<?php

namespace Drupal\renderkit\ListFormat;

/**
 * Concatenates the list items with a separator.
 */
class SeparatorListFormat implements ListFormatInterface {

  /**
   * @var string
   */
  private $separator;

  /**
   * @param string $separator
   */
  function __construct($separator = '') {
    $this->separator = $separator;
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
    return array(
      /* @see renderkit_theme() */
      /* @see theme_renderkit_separator_list() */
      '#theme' => 'renderkit_separator_list',
      '#separator' => $this->separator,
    ) + $builds;
  }
}
