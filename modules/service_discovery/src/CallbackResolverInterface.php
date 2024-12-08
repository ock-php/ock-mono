<?php

/**
 * @file
 */

declare(strict_types=1);

namespace Drupal\service_discovery;

/**
 * A service to autowire constructor and factory signatures.
 */
interface CallbackResolverInterface {

  /**
   * @param array<int, mixed> $args
   *   Positional argument values.
   *
   * @return static
   */
  public function withKnownArgs(array $args): static;

  /**
   * @param list<mixed> $trailingArgs
   *   Argument values for the last non-variadic parameters.
   *
   * @return static
   */
  public function withTrailingArgs(array $trailingArgs): static;

  /**
   * @param list<mixed> $variadicArgs
   *   Argument values after the last non-variadic parameter.
   *
   * @return static
   */
  public function withVariadicArgs(array $variadicArgs): static;

  /**
   * @param array<string, mixed> $args
   *   Argument values by parameter name.
   *
   * @return static
   */
  public function withNamedArgs(array $args): static;

  /**
   * @param array $args
   *
   * @return static
   */
  public function withTypeArgs(array $args): static;

  /**
   * @template T of object
   *
   * @param class-string<T> $class
   *
   * @return T&object
   *
   * @throws \Ock\DID\Exception\ContainerToValueException
   */
  public function construct(string $class): object;

  /**
   * @template T
   *
   * @param callable(): T $callback
   *
   * @return T
   *
   * @throws \Ock\DID\Exception\ContainerToValueException
   */
  public function call(callable $callback): mixed;

}
