<?php

namespace Drupal\renderkit\ListFormat;

use Drupal\renderkit\Html\HtmlTagTrait;

class ContainerListFormat implements ListFormatInterface {

  use HtmlTagTrait;

  /**
   * @param array[] $builds
   *   Array of render arrays for list items.
   *   Must not contain any property keys like "#..".
   *
   * @return array
   *   Render array for the list.
   */
  function buildList(array $builds) {
    return $this->buildContainer() + $builds;
  }
}
