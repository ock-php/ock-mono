<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

use Drupal\Component\Discovery\DiscoveryException;
use Drupal\ock\DI\ParamToServiceArg\ParamToServiceArgInterface;
use Ock\DID\Attribute\Parameter\GetService;
use Ock\DID\Attribute\Service;
use Ock\DID\Exception\ContainerToValueException;
use Ock\DID\Util\ReflectionUtil;

/**
 * Implements the class resolver interface supporting class names and services.
 */
#[Service]
class OckCallbackResolver {

  /**
   * @var array<int, mixed>
   */
  private array $args = [];

  /**
   * @var list<mixed>
   */
  private array $trailingArgs = [];

  /**
   * @var list<mixed>
   */
  private array $variadicArgs = [];

  /**
   * Constructor.
   *
   * @param \Drupal\ock\DI\ParamToServiceArg\ParamToServiceArgInterface $paramToServiceArg
   */
  public function __construct(
    #[GetService]
    private ParamToServiceArgInterface $paramToServiceArg,
  ) {}

  /**
   * @param array<int, mixed> $args
   *   Positional argument values.
   *
   * @return static
   */
  public function withKnownArgs(array $args): static {
    $clone = clone $this;
    $clone->args = $args;
    return $clone;
  }

  /**
   * @param list<mixed> $trailingArgs
   *   Argument values for the last non-variadic parameters.
   *
   * @return static
   */
  public function withTrailingArgs(array $trailingArgs): static {
    $clone = clone $this;
    $clone->trailingArgs = $trailingArgs;
    return $clone;
  }

  /**
   * @param list<mixed> $variadicArgs
   *   Argument values after the last non-variadic parameter.
   *
   * @return static
   */
  public function withVariadicArgs(array $variadicArgs): static {
    $clone = clone $this;
    $clone->variadicArgs = $variadicArgs;
    return $clone;
  }

  /**
   * @param array<string, mixed> $args
   *   Argument values by parameter name.
   *
   * @return static
   */
  public function withNamedArgs(array $args): static {
    $clone = clone $this;
    $clone->paramToServiceArg = $this->paramToServiceArg->withNamedArgs($args);
    return $clone;
  }

  /**
   * @param array $args
   *
   * @return static
   */
  public function withTypeArgs(array $args): static {
    $clone = clone $this;
    $clone->paramToServiceArg = $this->paramToServiceArg->withTypeArgs($args);
    return $clone;
  }

  /**
   * @template T
   *
   * @param class-string<T> $class
   *
   * @return T
   *
   * @throws \Ock\DID\Exception\ContainerToValueException
   */
  public function construct(string $class): object {
    try {
      $rClass = new \ReflectionClass($class);
    }
    catch (\ReflectionException $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
    $rConstructor = $rClass->getConstructor();
    if ($rConstructor === NULL) {
      return new $class();
    }
    $parameters = $rConstructor->getParameters();
    $args = $this->buildArgs($parameters);
    try {
      return new $class(...$args);
    }
    catch (\Throwable $e) {
      throw new DiscoveryException(sprintf(
        'Error while instantiating %s: %s',
        $class,
        $e->getMessage(),
      ), 0, $e);
    }
  }

  /**
   * @template T
   *
   * @param callable(): T $callback
   *
   * @return T
   *
   * @throws \Ock\DID\Exception\ContainerToValueException
   */
  public function call(callable $callback): mixed {
    try {
      $rFunction = ReflectionUtil::reflectCallable($callback);
    }
    catch (\ReflectionException $e) {
      throw new ContainerToValueException($e->getMessage(), 0, $e);
    }
    $parameters = $rFunction->getParameters();
    $args = $this->buildArgs($parameters);
    return $callback(...$args);
  }

  /**
   * @param array $parameters
   *
   * @return list<mixed>
   *
   * @throws \Ock\DID\Exception\ContainerToValueException
   */
  private function buildArgs(array $parameters): array {
    if (!$parameters) {
      return [];
    }
    $last = end($parameters);
    if ($last->isVariadic()) {
      array_pop($parameters);
    }
    $args = $this->args;
    if ($this->trailingArgs) {
      $offsetTrailing = count($parameters) - count($this->trailingArgs);
      foreach ($this->trailingArgs as $i => $trailingArg) {
        $args[$i + $offsetTrailing] = $trailingArg;
      }
    }
    $parameters = array_diff_key($parameters, $args);
    foreach ($parameters as $i => $parameter) {
      $arg = $this->paramToServiceArg->paramGetServiceArg($parameter);
      $arg->
      $args[$i] = $this->paramToServiceArg->paramGetValue($parameter);
    }
    ksort($args);
    if ($this->variadicArgs) {
      $args = [...$args, ...$this->variadicArgs];
    }
    return $args;
  }

}
