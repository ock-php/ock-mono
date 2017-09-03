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
   * @return $this
   */
  public function setTagName($tagName) {
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
