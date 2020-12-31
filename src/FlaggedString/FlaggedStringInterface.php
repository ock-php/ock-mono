<?php

namespace Donquixote\Cf\FlaggedString;

use Donquixote\Cf\Stringable\StringableInterface;

interface FlaggedStringInterface extends StringableInterface {

  /**
   * Checks if the string has a given tag.
   *
   * @param string $flag_name
   *   Name of a tag.
   *
   * @return bool
   *   TRUE, if the string has the tag.
   */
  public function is(string $flag_name): bool;

}
