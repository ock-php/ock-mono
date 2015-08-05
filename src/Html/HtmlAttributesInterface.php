<?php

namespace Drupal\renderkit\Html;

interface HtmlAttributesInterface {

  /**
   * @param string $class
   *
   * @return $this
   */
  function addClass($class);

  /**
   * @param string[] $classes
   *
   * @return $this
   */
  function addClasses(array $classes);

}
