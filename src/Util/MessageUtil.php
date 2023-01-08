<?php

declare(strict_types=1);

namespace Donquixote\CodegenTools\Util;

class MessageUtil {

  /**
   * Formats a value for use in messages.
   *
   * @param mixed $value
   *
   * @return string
   */
  public static function formatValue(mixed $value): string {
    switch (gettype($value)) {
      case 'object':
        if ($value instanceof \Closure) {
          try {
            $rf = new \ReflectionFunction($value);
          }
          catch (\ReflectionException $e) {
            throw new \RuntimeException('Failed to reflect closure', 0, $e);
          }
          return 'Closure at ' . $rf->getFileName() . ':' . $rf->getStartLine();
        }
        return get_class($value) . ' object';

      case 'array':
        if ($value === []) {
          return '[]';
        }
        if (\count($value) < 3 && \array_is_list($value)) {
          return '['
            . implode(', ', \array_map(
              [self::class, 'formatValue'],
              $value,
            ))
            . ']';
        }
        return '[..]';

      case 'boolean':
      case 'integer':
      case 'double':
      case 'string':
      case 'NULL':
        return \var_export($value, true);

      case 'resource':
      case 'resource (closed)':
        return \gettype($value);

      default:
        return \get_debug_type($value) . ' value';
    }
  }

  /**
   * Gets a name for a reflector to use in messages.
   *
   * @param \ReflectionClassConstant|\ReflectionParameter|\ReflectionClass|\ReflectionProperty|\ReflectionFunctionAbstract $reflector
   *
   * @return string
   */
  public static function formatReflector(
    \ReflectionClassConstant|\ReflectionParameter|\ReflectionClass|\ReflectionProperty|\ReflectionFunctionAbstract $reflector,
  ): string {
    $name = $reflector->getName();
    if ($reflector instanceof \ReflectionParameter) {
      return \sprintf(
        'parameter $%s of %s',
        $name,
        self::formatReflector($reflector->getDeclaringFunction()),
      );
    }
    if ($reflector instanceof \ReflectionFunctionAbstract) {
      $name .= '()';
    }
    if ($reflector instanceof \ReflectionProperty) {
      $name = '$' . $name;
    }
    if ($reflector instanceof \ReflectionProperty || $reflector instanceof \ReflectionMethod) {
      $glue = $reflector->isStatic() ? '::' : '->';
      $name = $reflector->getDeclaringClass()->getName() . $glue . $name;
    }
    elseif ($reflector instanceof \ReflectionClassConstant) {
      $name = $reflector->getDeclaringClass()->getName() . '::' . $name;
    }
    return $name;
  }

}
