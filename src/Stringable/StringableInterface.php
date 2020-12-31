<?php

namespace Donquixote\Cf\Stringable;

/**
 * Replicates the PHP 8.0 Stringable interface.
 */
interface StringableInterface {

  /**
   * Gets the string value of the object.
   *
   * @return string
   */
  public function __toString(): string;

}
