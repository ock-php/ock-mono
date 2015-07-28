<?php

namespace Drupal\renderkit\Attributes;

/**
 * @see TagInterface
 */
trait TagTrait {

  use AttributesTrait;

  /**
   * @var string
   */
  protected $tagName = 'div';

  /**
   * @param string $tagName
   *
   * @return $this
   */
  function setTagName($tagName) {
    $this->tagName = $tagName;
    return $this;
  }

  /**
   * @return array
   */
  protected function buildContainer() {
    return $this->tagNameBuildContainer($this->tagName);
  }
}
