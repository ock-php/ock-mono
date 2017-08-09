<?php

namespace Drupal\renderkit8\Html;

interface HtmlTagInterface extends HtmlAttributesInterface {

  /**
   * @param string $tagName
   *
   * @return $this
   */
  public function setTagName($tagName);

}
