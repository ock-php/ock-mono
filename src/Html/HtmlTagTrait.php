<?php

namespace Drupal\renderkit8\Html;

trait HtmlTagTrait {

  use HtmlAttributesTrait;

  /**
   * @var string
   */
  private $tagName = 'div';

  /**
   * @param string $tagName
   *
   * @return static
   */
  public function setTagName($tagName) {
    $clone = clone $this;
    $clone->tagName = $tagName;
    return $clone;
  }

  /**
   * @return array
   */
  protected function buildContainer() {
    return $this->tagNameBuildContainer($this->tagName);
  }
}
