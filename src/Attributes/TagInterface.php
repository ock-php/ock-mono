<?php

namespace Drupal\renderkit\Attributes;

interface TagInterface extends AttributesInterface {

  /**
   * @param string $tagName
   *
   * @return $this
   */
  function setTagName($tagName);

}
