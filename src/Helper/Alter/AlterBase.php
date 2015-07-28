<?php

namespace Drupal\renderkit\Helper\Alter;

class AlterBase {

  const TYPE = NULL;

  /**
   * @var string[]
   */
  private $functions;

  /**
   * @return static
   */
  static function create() {
    $drupalAlter = new DrupalAlter();
    $functions = !is_null(static::TYPE)
      ? $drupalAlter->getFunctions(static::TYPE)
      : array();
    return new static($functions);
  }

  /**
   * @param string[] $functions
   */
  function __construct(array $functions) {
    $this->functions = $functions;
  }

  /**
   * @return string[]
   */
  protected function getFunctions() {
    return $this->functions;
  }

}
