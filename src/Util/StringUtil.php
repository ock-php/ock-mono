<?php
declare(strict_types=1);

namespace Drupal\cu\Util;

final class StringUtil extends UtilBase {

  /**
   * Credits:
   *   - Charl van Niekerk,
   *     http://blog.charlvn.za.net/2007/11/php-camelcase-explode-20.html
   *   - Andreas Hennings / dqxtech
   *     http://dqxtech.net/blog/2011-03-04/php-camelcaseexplode-xl-version
   *
   * @param string $string
   *   The original string, that we want to explode.
   *
   * @param bool $lowercase
   *   should the result be lowercased?
   *
   * @param string $example_string
   *   Example to specify how to deal with multiple uppercase characters.
   *   Can be something like "AA Bc" or "A A Bc" or "AABc".
   *
   * @param string|bool $glue
   *   Allows to implode the fragments with sth like "_" or "." or " ".
   *   If $glue is FALSE, it will just return an array.
   *
   * @return string[]|string
   */
  public static function camelCaseExplode($string, $lowercase = true, $example_string = 'AA Bc', $glue = false) {
    static $regexp_by_example = [];
    if (!isset($regexp_by_example[$example_string])) {
      $regexp_by_example[$example_string] = self::camelCaseExplodeExampleToRegex($example_string);
    }
    $array = self::camelCaseExplodeWithRegex($regexp_by_example[$example_string], $string);
    if ($lowercase) {
      $array = array_map('strtolower', $array);
    }
    return \is_string($glue) ? implode($glue, $array) : $array;
  }

  /**
   * @param string $regexp
   * @param string $string
   *
   * @return string[]
   */
  public static function camelCaseExplodeWithRegex($regexp, $string): array {
    return preg_split($regexp, $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
  }

  /**
   * @param string $example_string
   *
   * @return string
   *   Regular expression to use.
   */
  public static function camelCaseExplodeExampleToRegex($example_string): string {
    static $regexp_available = [
      '/([A-Z][^A-Z]*)/',
      '/([A-Z][^A-Z]+)/',
      '/([A-Z]+[^A-Z]*)/',
    ];
    $orig = str_replace(' ', '', $example_string);
    $expected_exploded = explode(' ', $example_string);
    foreach ($regexp_available as $regexp) {
      $actual_exploded = self::camelCaseExplodeWithRegex($regexp, $orig);
      if ($expected_exploded === $actual_exploded) {
        return $regexp;
      }
    }
    throw new \InvalidArgumentException('Invalid example string.');
  }

  /**
   * @param string $interface
   *
   * @return string
   */
  public static function interfaceGenerateLabel($interface): string {
    $title = $interface;
    if (FALSE !== $pos = strrpos($title, '\\')) {
      $title = substr($title, $pos + 1);
    }
    if ('Interface' === substr($title, -9) && 'Interface' !== $title) {
      $title = substr($title, 0, -9);
    }
    return self::methodNameGenerateLabel($title);
  }

  /**
   * @param string $class
   *
   * @return string
   */
  public static function classGetShortname($class): string {
    return (FALSE !== $pos = strrpos($class, '\\'))
      ? substr($class, $pos + 1)
      : $class;
  }

  /**
   * @param string $methodName
   *
   * @return string
   */
  public static function methodNameGenerateLabel($methodName): string {
    return ucfirst(self::camelCaseExplode($methodName, TRUE, 'AA Bc', ' '));
  }

}
