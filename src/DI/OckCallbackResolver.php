<?php

namespace Drupal\ock\DI;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
use Donquixote\Ock\Util\ReflectionUtil;
use Drupal\ock\Attribute\DI\RegisterService;

/**
 * Implements the class resolver interface supporting class names and services.
 */
#[RegisterService]
class OckCallbackResolver {

  private $args = [];

  private $trailingArgs = [];

  private $variadicArgs = [];

  /**
   * Constructor.
   *
   * @param \Drupal\ock\DI\OckParameterResolver $parameterResolver
   */
  public function __construct(
    #[GetService]
    private OckParameterResolver $parameterResolver,
  ) {}

  public function withKnownArgs(array $args): static {
    $clone = clone $this;
    $clone->args = $args;
    return $clone;
  }

  public function withTrailingArgs(array $trailingArgs): static {
    $clone = clone $this;
    $clone->trailingArgs = $trailingArgs;
    return $clone;
  }

  public function withVariadicArgs(array $variadicArgs): static {
    $clone = clone $this;
    $clone->variadicArgs = $variadicArgs;
    return $clone;
  }

  public function withNamedArgs(array $args): static {
    $clone = clone $this;
    $clone->parameterResolver = $this->parameterResolver->withNamedArgs($args);
    return $clone;
  }

  public function withTypeArgs(array $args): static {
    $clone = clone $this;
    $clone->parameterResolver = $this->parameterResolver->withTypeArgs($args);
    return $clone;
  }

  /**
   * @template T
   *
   * @param class-string<T> $class
   *
   * @return T
   *
   * @throws \Exception
   */
  public function construct(string $class): object {
    $rClass = new \ReflectionClass($class);
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
      throw $e;
    }
  }

  /**
   * @template T
   *
   * @param callable(): T $callback
   *
   * @return T
   *
   * @throws \ReflectionException
   */
  public function call(callable $callback): mixed {
    $rFunction = ReflectionUtil::reflectCallable($callback);
    $parameters = $rFunction->getParameters();
    $args = $this->buildArgs($parameters);
    return $callback(...$args);
  }

  /**
   * @param array $parameters
   *
   * @return array
   * @throws \Exception
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
      $args[$i] = $this->parameterResolver->resolveParameter($parameter);
    }
    ksort($args);
    if ($this->variadicArgs) {
      $args = [...$args, ...$this->variadicArgs];
    }
    return $args;
  }

}
