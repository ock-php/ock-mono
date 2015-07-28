<?php

namespace Drupal\renderkit\Attributes;

interface AttributesInterface {

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
