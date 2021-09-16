<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

use Donquixote\CallbackReflection\Util\CodegenUtil;

final class PhpUtil extends UtilBase {

  # public const REGEX_IDENTIFIER_CHAR_FIRST = /** @lang RegExp */ '[a-zA-Z_\x7f-\xff]';
  # public const REGEX_IDENTIFIER_CHAR_OTHER = /** @lang RegExp */ '[a-zA-Z0-9_\x7f-\xff]';
  # public const REGEX_IDENTIFIER_PATTERN = self::REGEX_IDENTIFIER_CHAR_FIRST . self::REGEX_IDENTIFIER_CHAR_OTHER . '*';
  # public const REGEX_IDENTIFIER = '/^' . self::REGEX_IDENTIFIER_PATTERN . '$/';
  # public const REGEX_NAMESPACE = '/^(?:' . self::REGEX_IDENTIFIER_PATTERN . '\\\\)*' . self::REGEX_IDENTIFIER_PATTERN . '$/';

  /**
   * @param string $php
   * @param string|null $namespace
   *
   * @return string
   */
  public static function formatAsFile(string $php, $namespace = NULL): string {

    $php = CodegenUtil::autoIndent($php, '  ');
    $aliases = CodegenUtil::aliasify($php);

    $aliases_php = '';
    foreach ($aliases as $class => $alias) {
      if (TRUE === $alias) {
        $aliases_php .= 'use ' . $class . ";\n";
      }
      else {
        $aliases_php .= 'use ' . $class . ' as ' . $alias . ";\n";
      }
    }

    if ('' !== $aliases_php) {
      $aliases_php = "\n" . $aliases_php;
    }

    $php = <<<EOT
$aliases_php

$php
EOT;

    if (NULL !== $namespace) {
      $php = <<<EOT
namespace $namespace;
$php
EOT;

    }

    return <<<EOT
<?php
$php
EOT;

  }

  /**
   * @param string $objectPhp
   * @param string $method
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallMethod(string $objectPhp, string $method, array $argsPhp): string {
    return self::phpCallFqn($objectPhp . '->' . $method, $argsPhp);
  }

  /**
   * @param callable $method
   *   Static method, as an array of two strings.
   * @param string[] $argsPhp
   *   Arguments as php expressions.
   *
   * @return string
   *   Php expression that calls the static method.
   */
  public static function phpCallStatic(callable $method, array $argsPhp): string {
    if (!is_array($method) || !is_string($method[0])) {
      throw new \InvalidArgumentException('Parameter $method must be a static method.');
    }
    return self::phpCallFqn('\\' . $method[0] . '::' . $method[1], $argsPhp);
  }

  /**
   * @param callable $callable
   *   Static method or function.
   * @param string[] $argsPhp
   *   Arguments as php expressions.
   *
   * @return string
   *   Php expression that calls the static method.
   */
  public static function phpCall(callable $callable, array $argsPhp): string {
    if (is_array($callable)) {
      if (!is_string($callable[0])) {
        throw new \InvalidArgumentException('Parameter must be a static method.');
      }
      return self::phpCallFqn('\\' . $callable[0] . '::' . $callable[1], $argsPhp);
    }
    if (is_string($callable)) {
      return self::phpCallFqn('\\' . $callable, $argsPhp);
    }
    throw new \InvalidArgumentException('Expected a static method or a function name.');
  }

  /**
   * @param string $class
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpNewClass(string $class, array $argsPhp): string {
    return self::phpCallFqn('new \\' . $class, $argsPhp);
  }

  /**
   * @param string|callable $function
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallFunction(string $function, array $argsPhp): string {
    return self::phpCallFqn('\\' . $function, $argsPhp);
  }

  /**
   * @param string $fqn
   * @param string[] $argsPhp
   *
   * @return string
   */
  public static function phpCallFqn(string $fqn, array $argsPhp): string {
    $php = $fqn . self::phpCallArglist($argsPhp);
    if (strlen($php) > 80 || FALSE !== strpos($php, "\n")) {
      // Code is too long for a single line, or already contains line breaks.
      // Insert line breaks between arguments.
      // This is a temporary solution to make tests pass.
      // @todo Insert line breaks as a post-processing step instead.
      $php = $fqn . self::phpCallArglist($argsPhp, TRUE);
    }
    return $php;
  }

  /**
   * @param string[] $argsPhp
   * @param bool $break
   *
   * @return string
   */
  public static function phpCallArglist(array $argsPhp, bool $break = FALSE): string {
    return ($break ? "(\n" : '(')
      . implode($break ? ",\n" : ", ", $argsPhp)
      . ')';
  }

  /**
   * Exports a simple value as a php value expression.
   *
   * Throws an "unchecked" exception on failure.
   *
   * @param mixed $value
   *   Simple value that is known to be safe for export.
   *
   * @return string
   *   PHP value expression.
   */
  public static function phpValueSimple($value): string {
    try {
      return self::phpValue($value);
    }
    catch (\Exception $e) {
      // Convert to an "unchecked" exception.
      throw new \InvalidArgumentException(
        sprintf(
          'Value %s was not as simple as expected.',
          MessageUtil::formatValue($value)),
        0,
        $e);
    }
  }

  /**
   * Attempts to exports a value as a PHP value expression.
   *
   * Throws a "checked" exception on failure.
   *
   * @param mixed $value
   *   Value that is hoped to be safe for export.
   *
   * @return string
   *   PHP value expression.
   *
   * @throws \Exception
   *   Value cannot be exported.
   */
  public static function phpValue($value): string {

    if (\is_array($value)) {

      $valuesPhp = [];
      foreach ($value as $k => $v) {
        $valuesPhp[$k] = self::phpValue($v);
      }

      return self::phpArray($valuesPhp);
    }

    if (\is_object($value)) {
      return self::phpObject($value);
    }

    if (\is_string($value)) {
      return self::phpString($value);
    }

    return var_export($value, TRUE);
  }

  /**
   * @param object $object
   *
   * @return string
   *
   * @throws \Exception
   *   Object cannot be exported.
   */
  public static function phpObject(object $object): string {

    /**
     * @noinspection PhpConditionAlreadyCheckedInspection
     *   See https://youtrack.jetbrains.com/issue/WI-62543.
     */
    if ($object instanceof \stdClass) {
      return '(object) ' . self::phpArray((array) $object);
    }

    if ($object instanceof \Closure) {
      throw new \Exception('Cannot export closure.');
    }

    $valuess = ReflectionUtil::objectGetPropertyValuesDeep($object) + [0 => []];
    $values = $valuess[0];
    unset($valuess[0]);

    $argsPhp = [
      '\\' . \get_class($object) . '::class',
      self::phpValue($values),
    ];

    if ([] !== $valuess) {
      $argsPhp[] = self::phpValue($valuess);
    }

    return self::phpCallStatic(
      [ReflectionUtil::class, 'createInstance'],
      $argsPhp);
  }

  /**
   * @param string $string
   *
   * @return string
   */
  public static function phpString(string $string): string {
    return var_export($string, TRUE);
  }

  /**
   * @param string[] $valuesPhp
   *
   * @return string
   */
  public static function phpArray(array $valuesPhp): string {

    if ([] === $valuesPhp) {
      return '[]';
    }

    $php = '';
    if (array_keys($valuesPhp) === range(0, count($valuesPhp) - 1)) {
      foreach ($valuesPhp as $vPhp) {
        $php .= "\n  $vPhp,";
      }
    }
    else {
      foreach ($valuesPhp as $k => $vPhp) {
        $kPhp = var_export($k, TRUE);
        $php .= "\n  $kPhp => $vPhp,";
      }
    }

    return "[$php\n]";
  }

}
