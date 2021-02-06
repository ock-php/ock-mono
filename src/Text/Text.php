<?php

namespace Donquixote\OCUI\Text;

/**
 * Utility class with static methods.
 */
class Text {

  /**
   * Builds a text object for a select option with '- ... -'.
   *
   * @param string $string
   *   Original untranslated text with placeholders.
   * @param array $replacements
   *   Replacements.
   */
  public static function tSpecialOption(string $string, array $replacements = []) {
    return static::t('- @option -', [
      '@option' => static::t($string, $replacements),
    ]);
  }

  public static function tParens(string $string, array $replacements = []) {
    return static::t('(@text)', [
      '@text' => static::t($string, $replacements),
    ]);
  }

  /**
   * Builds a text object for "Label: Value".
   *
   * @param \Donquixote\OCUI\Text\TextInterface $label
   *   Label.
   * @param \Donquixote\OCUI\Text\TextInterface $value
   *   Value.
   */
  public static function label(TextInterface $label, TextInterface $value) {
    return static::t('@label: @value', [
      '@label' => $label,
      '@value' => $value,
    ]);
  }

  /**
   * Gets a translatable text object.
   *
   * @param string $string
   *   Original untranslated text with placeholders.
   * @param \Donquixote\OCUI\Text\TextInterface[] $replacements
   *   Replacements.
   *
   * @return \Donquixote\OCUI\Text\TextInterface
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
   * @param \Donquixote\OCUI\Text\TextInterface[] $replacements
   *   Replacements.
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   *   Text object.
   */
  public static function s(string $string, array $replacements = []) {
    $text = new Text_Raw($string);
    if ($replacements) {
      $text = new Text_Replacements($text, $replacements);
    }
    return $text;
  }

  /**
   * Builds a text object for an integer number.
   *
   * @param int $number
   *   Integer number.
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   *   Text object.
   */
  public static function i(int $number) {
    return new Text_Raw((string) $number);
  }

  /**
   * Builds a text object for a html list with <ul>.
   *
   * @param \Donquixote\OCUI\Text\TextInterface[] $parts
   *   List items.
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   *   Translatable text object.
   */
  public static function ul(array $parts) {
    return static::ulOrOl($parts, 'ul');
  }

  /**
   * Builds a text object for a html list with <ol>.
   *
   * @param \Donquixote\OCUI\Text\TextInterface[] $parts
   *   List items.
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   *   Translatable text object.
   */
  public static function ol(array $parts) {
    return static::ulOrOl($parts, 'ul');
  }

  /**
   * Builds a text object for a html list with <ul> or <ol>.
   *
   * @param \Donquixote\OCUI\Text\TextInterface[] $parts
   *   List items.
   * @param string $tag
   *   One of 'ul' or 'ol'.
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   *   Translatable text object.
   */
  protected static function ulOrOl(array $parts, string $tag) {
    $string = '';
    $replacements = [];
    foreach (array_values($parts) as $i => $part) {
      $key = '@' . $i;
      $string .= "<li>$key</li>";
      $replacements[$key] = $part;
    }
    return new Text_Replacements(
      new Text_Raw("<$tag>$string</$tag>"),
      $replacements);
  }

  /**
   * Gets a non-translatable text object.
   *
   * @param \Donquixote\OCUI\Text\TextInterface[] $parts
   *   List items.
   * @param string $glue
   *   Glue string between the items.
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   *   Translatable text object.
   */
  public static function concat(array $parts, string $glue = '') {
    $replacements = [];
    foreach (array_values($parts) as $i => $part) {
      $replacements['@' . $i] = $part;
    }
    return static::s(
      implode($glue, array_keys($replacements)),
      $replacements);
  }

}
