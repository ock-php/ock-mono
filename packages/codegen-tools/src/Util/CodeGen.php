<?php

declare(strict_types=1);

namespace Donquixote\CodegenTools\Util;

use Donquixote\CodegenTools\ValueExporter;

final class CodeGen {

  /**
   * @param string $enclosedObjectPhp
   * @param string $method
   * @param string[] $argsPhp
   *
   * @return string
   *   PHP expression.
   */
  public static function phpCallMethod(string $enclosedObjectPhp, string $method, array $argsPhp): string {
    return self::phpCallFqn($enclosedObjectPhp . '->' . $method, $argsPhp);
  }

  /**
   * @param callable $method
   *   Static method, as an array of two strings.
   * @param string[] $argsPhp
   *   Arguments as php expressions.
   *
   * @return string
   *   PHP expression.
   */
  public static function phpCallStatic(callable $method, array $argsPhp = []): string {
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
   *   PHP expression.
   */
  public static function phpCall(callable $callable, array $argsPhp = []): string {
    if (is_array($callable)) {
      if (!is_string($callable[0])) {
        throw new \InvalidArgumentException('Parameter must be a static method or a function.');
      }
      return self::phpCallFqn('\\' . $callable[0] . '::' . $callable[1], $argsPhp);
    }
    if (is_string($callable)) {
      return self::phpCallFqn('\\' . $callable, $argsPhp);
    }
    throw new \InvalidArgumentException('Expected a static method or a function name.');
  }

  /**
   * @param class-string $class
   * @param string[] $argsPhp
   * @param bool $enclose
   *   TRUE to wrap in ().
   *
   * @return string
   *   PHP expression.
   */
  public static function phpConstruct(string $class, array $argsPhp = [], bool $enclose = false): string {
    return self::phpCallFqn('new \\' . $class, $argsPhp, $enclose);
  }

  /**
   * @param string $classExpression
   *   Dynamic expression for the class name.
   * @param string[] $argsPhp
   * @param bool $enclose
   *   TRUE to wrap in ().
   *
   * @return string
   *   PHP expression.
   */
  public static function phpConstructDynamic(string $classExpression, array $argsPhp = [], bool $enclose = false): string {
    return self::phpCallFqn('new (' . $classExpression . ')', $argsPhp, $enclose);
  }

  /**
   * @param string $php
   * @param bool $enclose
   *
   * @return string
   *   PHP expression.
   */
  public static function phpEncloseIf(string $php, bool $enclose): string {
    return $enclose ? "($php)" : $php;
  }

  /**
   * @param callable-string $function
   * @param string[] $argsPhp
   *
   * @return string
   *   PHP expression.
   */
  public static function phpCallFunction(string $function, array $argsPhp): string {
    return self::phpCallFqn('\\' . $function, $argsPhp);
  }

  /**
   * @param string $fqn
   * @param list<string> $argsPhp
   * @param bool $enclose
   *
   * @return string
   *   PHP expression.
   */
  public static function phpCallFqn(string $fqn, array $argsPhp, bool $enclose = false): string {
    if ($argsPhp === []) {
      return $enclose ? "($fqn())" : "$fqn()";
    }
    $php = $fqn
      . self::phpArglistLimited(
        $argsPhp,
        80 - strlen($fqn) + strrpos($fqn, "\n"),
      );
    return $enclose ? "($php)" : $php;
  }

  /**
   * @param list<string> $argsPhp
   * @param int $limit
   *
   * @return string
   *   PHP expression.
   */
  public static function phpArglistLimited(array $argsPhp, int $limit): string {
    $php = self::phpArglist($argsPhp);
    if (strlen($php) <= $limit && !str_contains($php, "\n")) {
      return $php;
    }
    // Code is too long for a single line, or already contains line breaks.
    // Insert line breaks between arguments.
    // This is a temporary solution to make tests pass.
    // @todo Insert line breaks as a post-processing step instead.
    return self::phpArglist($argsPhp, TRUE);
  }

  /**
   * @param list<string> $argsPhp
   * @param bool $multiline
   *
   * @return string
   *   PHP expression.
   */
  public static function phpArglist(array $argsPhp, bool $multiline = FALSE): string {
    if ($argsPhp === []) {
      return '()';
    }
    if (!$multiline) {
      return '(' . implode(', ', $argsPhp) . ')';
    }
    return "(\n"
      . implode(",\n", $argsPhp)
      . ",\n)";
  }

  /**
   * Exports a simple value as a php value expression.
   *
   * Throws an "unchecked" exception on failure.
   * This should be used if the developer is fully in control of the values
   * being sent.
   *
   * @param mixed $value
   *   Simple value that is known to be safe for export.
   *
   * @return string
   *   PHP value expression.
   */
  public static function phpValueUnchecked(mixed $value): string {
    try {
      return self::phpValue($value);
    }
    catch (\Exception $e) {
      // Convert to an "unchecked" exception.
      throw new \InvalidArgumentException($e->getMessage(), 0, $e);
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
  public static function phpValue(mixed $value): string {
    return (new ValueExporter())->export($value);
  }

  /**
   * Exports a value that is scalar or null.
   *
   * This is a dedicated function because:
   * - Scalar values are guaranteed exportable, no exception will occur.
   * - No if/else needed.
   * - Function can be passed as a callable, unlike var_export().
   *
   * @param string|int|bool|float|null $string
   *
   * @return string
   */
  public static function phpScalar(string|int|bool|null|float $string): string {
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

    $parts = [];
    if (\array_is_list($valuesPhp)) {
      $parts = $valuesPhp;
    }
    else {
      $i = 0;
      foreach ($valuesPhp as $k => $vPhp) {
        if ($k === $i) {
          $parts[] = $vPhp;
          ++$i;
        }
        else {
          $kPhp = var_export($k, TRUE);
          $parts[] = $kPhp . ' => ' . $vPhp;
        }
      }
    }

    $php = implode(', ', $parts);
    if (strlen($php) < 80) {
      return "[$php]";
    }

    $php = implode(",\n  ", $parts);

    return "[\n$php,\n]";
  }

}
