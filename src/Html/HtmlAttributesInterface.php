<?php

namespace Drupal\renderkit8\Html;

interface HtmlAttributesInterface {

  /**
   * @param string $class
   *
   * @return $this
   */
  public function addClass($class);

  /**
   * @param string[] $classes
   *
   * @return $this
   */
  public function addClasses(array $classes);

}
