<?php

declare(strict_types=1);

namespace Drupal\ock\DI;

class ContainerExpressionUtil {

  const OP_METHOD = 'method';

  const OP_CALL = 'call';

  /**
   * @template T
   *
   * @param class-string<T> $class
   * @param array $args
   * @param array $ops
   *
   * @return T
   */
  public static function construct(string $class, array $args, array $ops): object {
    $args = self::processArgs($args, $ops);
    return new $class(...$args);
  }

  /**
   * @template T
   *
   * @param callable(mixed...): T $callback
   * @param array $args
   * @param array $ops
   *
   * @return T
   */
  public static function call(callable $callback, array $args, array $ops): mixed {
    $args = self::processArgs($args, $ops);
    return $callback(...$args);
  }

  /**
   * @param array $args
   * @param array $ops
   *
   * @return array
   */
  private static function processArgs(array $args, array $ops): array {
    foreach ($ops as $k => $op) {
      $args[$k] = self::processArg($op, $args[$k]);
    }
    return $args;
  }

  /**
   * @param mixed $op
   * @param array $array
   *
   * @return mixed
   */
  private static function processArg(mixed $op, array $array): mixed {
    if (is_string($op)) {
      switch ($op) {
        case self::OP_CALL:
          $callback = $array['callback'];
          $args = $array['args'] ?? [];
          return $callback(...$args);

        case self::OP_METHOD:
          $object = $array['object'];
          $method = $array['method'];
          $args = $array['args'] ?? [];
          return $object->$method(...$args);

        default:
          break;
      }
    }
    throw new \RuntimeException('Unsupported operation.');
  }

}
