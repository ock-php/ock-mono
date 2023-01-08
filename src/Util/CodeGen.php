<?php

declare(strict_types=1);

namespace Donquixote\CodegenTools\Util;

use Donquixote\CodegenTools\Exception\CodegenException;

final class CodeGen {

  /**
   * @param string $objectPhp
   * @param string $method
   * @param string[] $argsPhp
   *
   * @return string
   *   PHP expression.
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
  public static function phpValue(mixed $value): string {

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
    if ($object instanceof \stdClass) {
      return '(object) ' . self::phpArray((array) $object);
    }

    if ($object instanceof \Closure) {
      throw new \Exception('Cannot export closure.');
    }

    if ($object instanceof \Reflector) {
      return self::phpReflector($object);
    }

    // Attempt to serialize.
    // If the class does not support it, an exception will be thrown.
    try {
      return \sprintf(
        '\\unserialize(%s)',
        self::phpString(\serialize($object)),
      );
    }
    catch (\Throwable $e) {
      throw new CodegenException('Cannot serialize the given object.', 0, $e);
    }
  }

  /**
   * @param \Reflector $reflector
   *
   * @return string
   *
   * @throws \Exception
   */
  public static function phpReflector(\Reflector $reflector): string {
    $args = match (get_class($reflector)) {
      \ReflectionClass::class => [$reflector->getName()],
      \ReflectionFunction::class => $reflector->isClosure()
        ? NULL
        : [$reflector->name],
      \ReflectionMethod::class,
      \ReflectionProperty::class,
      \ReflectionClassConstant::class => [
        $reflector->class,
        $reflector->name,
      ],
      default => NULL,
    };
    if ($args !== null) {
      return self::phpConstruct(
        get_class($reflector),
        array_map([self::class, 'phpValue'], $args),
      );
    }
    if ($reflector instanceof \ReflectionParameter) {
      return self::phpCallMethod(
        self::phpReflector($reflector->getDeclaringFunction()),
        'getParameter',
        $reflector->getName(),
      );
    }
    throw new CodegenException(sprintf('Cannot export %s object.', get_class($reflector)));
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

    $parts = [];
    if (\array_is_list($valuesPhp)) {
      $parts = $valuesPhp;
    }
    else {
      foreach ($valuesPhp as $k => $vPhp) {
        $kPhp = var_export($k, TRUE);
        $parts[] = $kPhp . ' => ' . $vPhp;
      }
    }

    $php = implode(', ', $parts);
    if (strlen($php) < 80) {
      return "[$php]";
    }

    $php = implode(",\n  ", $parts);

    return "[\n$php,\n]";
  }

  /**
   * Checks if a given type name is built-in.
   *
   * @param string $type
   *   The type name.
   *
   * @return bool
   *   TRUE for e.g. 'string', 'int' etc, FALSE for class names.
   */
  public static function typeIsBuiltin(string $type): bool {
    static $types_map = [
      // These values are not safe with the detection mechanism.
      'null' => true,
      'false' => true,
      'true' => true,
      // The 'resource' type will be interpreted as a class.
      'resource' => false,
      // Add some known types to speed things up.
      'int' => true,
      'bool' => true,
      'float' => true,
      'string' => true,
      'array' => true,
    ];
    return $types_map[$type]
      ??= $types_map[strtolower($type)]
      ??= self::determineIfTypeIsBuiltin($type);
  }

  /**
   * Checks if a given type name is built-in.
   *
   * @param string $type
   *   The type name.
   *
   * @return bool
   *   TRUE for e.g. 'string', 'int' etc, FALSE for class names.
   */
  private static function determineIfTypeIsBuiltin(string $type): bool {
    if (!preg_match('@^[a-zA-Z]+$@', $type)) {
      // The type name is not safe to use in eval().
      return FALSE;
    }
    $eval = "return static function (): $type {};";
    $f = eval($eval);
    try {
      $rf = new \ReflectionFunction($f);
    }
    catch (\ReflectionException) {
      return FALSE;
    }
    $rt = $rf->getReturnType();
    if (!$rt) {
      return FALSE;
    }
    return $rt->isBuiltin();
  }

  /**
   * Formats aliases as imports.
   *
   * @param mixed[] $aliases
   *   Format: $[$class] = $alias|true
   * @param string $prepend
   *   String to prepend if the list is not empty.
   *   This is useful to add line breaks.
   *
   * @return string
   *   Formatted PHP.
   */
  public static function formatAliases(array $aliases, string $prepend = "\n"): string {
    if (!$aliases) {
      return '';
    }
    ksort($aliases, \SORT_STRING | \SORT_FLAG_CASE);

    $aliases_php = '';
    foreach ($aliases as $class => $alias) {
      if (TRUE === $alias) {
        $aliases_php .= 'use ' . $class . ";\n";
      }
      else {
        $aliases_php .= 'use ' . $class . ' as ' . $alias . ";\n";
      }
    }

    return $prepend . $aliases_php;
  }

  /**
   * Prepends a 'return ' to a PHP value expression.
   *
   * @param string $expression
   *  PHP value expression.
   *
   * @return string
   *   PHP statement with added 'return ' statement.
   */
  public static function buildReturnStatement(string $expression): string {
    $php_full = '<?php ' . $expression;
    $tokens = token_get_all($php_full);
    $prefix = '';
    foreach ($tokens as $token) {
      if (is_string($token)) {
        break;
      }
      switch ($token[0]) {
        case \T_WHITESPACE:
        case \T_COMMENT:
        case \T_DOC_COMMENT:
        case \T_OPEN_TAG:
          break;

        default:
          break 2;
      }
      $prefix .= $token[1];
    }
    return substr($prefix, 6)
      . 'return '
      . substr($php_full, strlen($prefix))
      . ';';
  }

  /**
   * @param int|string $key
   *
   * @return string
   */
  public static function phpPlaceholder(int|string $key): string {
    return self::phpCallStatic([Expr::class, 'pl'], [
      \var_export($key, TRUE),
    ]);
  }

  /**
   * @param string $decoratedPhp
   *   Php decorated expresssion.
   * @param string $decoratorPhp
   *   Php expression that contains a placeholder for a decorated expression.
   *
   * @return string
   */
  public static function phpDecorate(string $decoratedPhp, string $decoratorPhp): string {
    return str_replace(
      self::phpPlaceholderDecorated(),
      $decoratedPhp,
      $decoratorPhp,
    );
  }

  /**
   * @param string $adapteePhp
   *   Php adaptee expresssion.
   * @param string $adapterPhp
   *   Php expression that contains a placeholder for a adaptee expression.
   *
   * @return string
   */
  public static function phpAdapt(string $adapteePhp, string $adapterPhp): string {
    return str_replace(
      self::phpPlaceholderAdaptee(),
      $adapteePhp,
      $adapterPhp,
    );
  }

  /**
   * @return string
   */
  public static function phpPlaceholderDecorated(): string {
    return self::phpCallStatic([Expr::class, 'plDecorated']);
  }

  /**
   * @return string
   */
  public static function phpPlaceholderAdaptee(): string {
    return self::phpCallStatic([Expr::class, 'plAdaptee']);
  }

}
