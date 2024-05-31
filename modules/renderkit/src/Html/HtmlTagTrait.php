<?php
declare(strict_types=1);

namespace Drupal\renderkit\Html;

trait HtmlTagTrait {

  use HtmlAttributesTrait;

  /**
   * @var string
   */
  private string $tagName = 'div';

  /**
   * @param string $tagName
   *
   * @return $this
   */
  public function setTagName(string $tagName): self {
    $this->tagName = $tagName;
    return $this;
  }

  /**
   * @param string $tagName
   *
   * @return static
   */
  public function withTagName(string $tagName): self {
    $clone = clone $this;
    $clone->tagName = $tagName;
    return $clone;
  }

  /**
   * @return array
   */
  protected function buildContainer(): array {
    return $this->tagNameBuildContainer($this->tagName);
  }

}
