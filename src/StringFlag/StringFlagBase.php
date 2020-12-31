<?php

namespace Donquixote\Cf\StringFlag;

use Donquixote\Cf\FlaggedString\FlaggedString;

abstract class StringFlagBase {

  /**
   * Wraps a string with the class as a flag.
   *
   * @param string $string
   *   The string.
   *
   * @return \Donquixote\Cf\FlaggedString\FlaggedString
   *   Flagged string object.
   */
  public static function create(string $string) {
    return new FlaggedString($string, [static::class]);
  }

  /**
   * Private constructor.
   *
   * Prevents that this class is ever instantiated.
   */
  private function __construct() {}

}
