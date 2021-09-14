<?php

declare(strict_types=1);

namespace Donquixote\Ock\Util;

class CallbackUtil {

  /**
   * @param $callable
   * @param array $argsPhp
   *
   * @return string
   */
  public static function callableGetPhp($callable, array $argsPhp): ?string {

    $arglistPhp = implode(', ', $argsPhp);

    switch (\gettype($callable)) {

      case 'array':
        list($classOrObject, $methodName) = $callable + [NULL, NULL];

        if (\is_string($classOrObject) && \is_string($methodName)) {
          // @todo validate!
          return "\\$classOrObject::$methodName($arglistPhp)";
        }

        return PhpUtil::exception(
          \RuntimeException::class,
          'Class and method name must be a string.');

      case 'object':
        return PhpUtil::exception(
        \RuntimeException::class,
        'Object callable not supported.');

      case 'string':
        // @todo validate!
        return "\\$callable($arglistPhp)";

      default:
        return PhpUtil::exception(
          \RuntimeException::class,
          'Not a callable.');
    }
  }

  /**
   * Gets a function-like reflector for a given callable.
   *
   * @param mixed $callable
   *   Callable definition.
   *
   * @return \ReflectionFunctionAbstract
   *   Reflector.
   *
   * @throws \ReflectionException
   *   Function or method does not exist.
   * @throws \Exception
   *   Malformed callable definition.
   */
  public static function callableGetReflector($callable): \ReflectionFunctionAbstract {

    if (\is_string($callable)) {
      return FALSE === strpos($callable, '::')
        ? new \ReflectionFunction($callable)
        : new \ReflectionMethod($callable);
    }

    if (\is_object($callable)) {
      if ($callable instanceof \Closure) {
        return new \ReflectionFunction($callable);
      }
      if (!method_exists($callable, '__invoke')) {
        return new \ReflectionMethod($callable, '__invoke');
      }
    }

    if (\is_array($callable)) {
      if (!isset($callable[0], $callable[1])) {
        throw new \Exception('Malformed callback array.');
      }

      return new \ReflectionMethod($callable[0], $callable[1]);
    }

    throw new \Exception('Malformed callback definition.');
  }

}
