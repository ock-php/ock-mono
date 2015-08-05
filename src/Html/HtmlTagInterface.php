<?php

namespace Drupal\renderkit\Html;

interface HtmlTagInterface extends HtmlAttributesInterface {

  /**
   * @param string $tagName
   *
   * @return $this
   */
  function setTagName($tagName);

}
