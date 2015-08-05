<?php

namespace Drupal\renderkit\Html;

/**
 * @see TagInterface
 */
trait HtmlTagTrait {

  use HtmlAttributesTrait;

  /**
   * @var string
   */
  private $tagName = 'div';

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
