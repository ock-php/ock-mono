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
   * @param \Donquixote\OCUI\Text\TextInterface[] $replacements
   *   Replacements.
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   */
  public static function tSpecialOption(string $string, array $replacements = []): TextInterface {
    return static::t('- @option -', [
      '@option' => static::t($string, $replacements),
    ]);
  }

  /**
   * @param string $string
   * @param \Donquixote\OCUI\Text\TextInterface[] $replacements
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   */
  public static function tParens(string $string, array $replacements = []): TextInterface {
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
   *
   * @return \Donquixote\OCUI\Text\TextInterface
   */
  public static function label(TextInterface $label, TextInterface $value): TextInterface {
    return static::t('@label: @value', [
      '@label' => $label,
      '@value' => $value,
    ]);
  }

  public static function tOrNull(?string $string, array $replacements = []): ?TextInterface {
    return ($string !== NULL)
      ? static::t($string, $replacements)
      : NULL;
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
  public static function t(string $string, array $replacements = []): TextInterface {
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
  public static function s(string $string, array $replacements = []): TextInterface {
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
  public static function i(int $number): TextInterface {
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
  public static function ul(array $parts): TextInterface {
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
  public static function ol(array $parts): TextInterface {
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
  protected static function ulOrOl(array $parts, string $tag): TextInterface {
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
  public static function concat(array $parts, string $glue = ''): TextInterface {
    $replacements = [];
    foreach (array_values($parts) as $i => $part) {
      $replacements['@' . $i] = $part;
    }
    return static::s(
      implode($glue, array_keys($replacements)),
      $replacements);
  }

  /**
   * Validates text objects.
   *
   * @param \Donquixote\OCUI\Text\TextInterface ...$texts
   */
  public static function validate(TextInterface ...$texts): void {}

  /**
   * Validates text objects.
   *
   * @param \Donquixote\OCUI\Text\TextInterface[] $texts
   *   Text objects to validate.
   */
  public static function validateMultiple(array $texts): void {
    self::validate(...array_values($texts));
  }

  /**
   * Validates arrays of text objects.
   *
   * @param \Donquixote\OCUI\Text\TextInterface[][] $textss
   *   Arrays of text objects.
   */
  public static function validateNested(array $textss): void {
    foreach ($textss as $texts) {
      self::validate(...array_values($texts));
    }
  }

}
