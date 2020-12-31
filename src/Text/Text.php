<?php

namespace Donquixote\Cf\Text;

/**
 * Utility class with static methods.
 */
class Text {

  /**
   * Gets text for a select option with '- ... -'.
   *
   * @param string $string
   *   Original untranslated text with placeholders.
   * @param array $replacements
   *   Replacements.
   */
  public static function option(string $string, array $replacements = []) {
    return static::t(
      '- @option -',
      [
        '@option' => static::t($string, $replacements),
      ]);
  }

  /**
   * Gets a translatable text object.
   *
   * @param string $string
   *   Original untranslated text with placeholders.
   * @param \Donquixote\Cf\Text\TextInterface[] $replacements
   *   Replacements.
   *
   * @return \Donquixote\Cf\Text\TextInterface
   *   Translatable text object.
   */
  public static function t(string $string, array $replacements = []) {
    $text = new Text_Translatable($string);
    if ($replacements) {
      $text = new Text_Replacements($text, $replacements);
    }
    return $text;
  }

  /**
   * Gets a non-translatable text object.
   *
   * @param string $string
   *   Original untranslated text with placeholders.
   * @param \Donquixote\Cf\Text\TextInterface[] $replacements
   *   Replacements.
   *
   * @return \Donquixote\Cf\Text\TextInterface
   *   Translatable text object.
   */
  public static function s(string $string, array $replacements = []) {
    $text = new Text_Raw($string);
    if ($replacements) {
      $text = new Text_Replacements($text, $replacements);
    }
    return $text;

  }

}
