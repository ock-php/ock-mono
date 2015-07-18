<?php

namespace Drupal\renderkit\EntityDisplay;

class EntityFixedHtml extends EntityFixedRenderArray {

  /**
   * @param string $html
   */
  function __construct($html) {
    parent::__construct(array('#markup' => $html));
  }
}
