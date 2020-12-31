<?php

namespace Donquixote\Cf\Text;

use Donquixote\Cf\Translatable\Translatable;

/**
 * Utility class with static methods.
 */
class Text {

  /**
   * Gets a translatable text object.
   *
   * @param string $string
   *   Original untranslated text with placeholders.
   * @param array $replacements
   *   Replacements.
   *
   * @return \Donquixote\Cf\Translatable\Translatable
   *   Translatable text object.
   */
  public static function t(string $string, array $replacements = []) {
    return new Translatable($string, $replacements);
  }

}
