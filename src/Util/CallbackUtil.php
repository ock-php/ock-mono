<?php
declare(strict_types=1);

namespace Donquixote\Cf\Util;

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
        list($classOrObject, $methodName) = $callable + [null, null];

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
   * @param mixed|callable $callable
   *
   * @return \ReflectionFunctionAbstract|null
   */
  public static function callableGetReflector($callable): ?\ReflectionFunctionAbstract {

    if (!\is_callable($callable)) {
      return NULL;
    }

    if (\is_string($callable)) {
      if (FALSE === strpos($callable, '::')) {
        if (!\function_exists($callable)) {
          return NULL;
        }
        return new \ReflectionFunction($callable);
      }
      else {
        return new \ReflectionMethod($callable);
      }
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
      if (isset($callable[0], $callable[1])) {
        return new \ReflectionMethod($callable[0], $callable[1]);
      }
    }

    return NULL;
  }

}
