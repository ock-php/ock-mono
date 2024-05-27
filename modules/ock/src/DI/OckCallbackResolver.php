<?php

declare(strict_types = 1);

namespace Drupal\ock\DI;

use Ock\DID\Exception\ContainerToValueException;
use Ock\Helpers\Util\MessageUtil;
use Ock\Ock\Util\ReflectionUtil;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\AutowiringFailedException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Implements the class resolver interface supporting class names and services.
 */
#[AsAlias(public: true)]
class OckCallbackResolver implements OckCallbackResolverInterface {

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
   * @var array<string, mixed>
   */
  private array $namedArgs = [];

  /**
   * @var array<string, mixed>
   */
  private array $typeArgs = [];

  /**
   * Constructor.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container.
   */
  public function __construct(
    private readonly ContainerInterface $container,
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
    $clone->namedArgs = $args;
    return $clone;
  }

  /**
   * @param array $args
   *
   * @return static
   */
  public function withTypeArgs(array $args): static {
    $clone = clone $this;
    $clone->typeArgs = $args;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function construct(string $class): object {
    try {
      $rClass = new \ReflectionClass($class);
    }
    // @phpstan-ignore-next-line
    catch (\ReflectionException|\Error $e) {
      throw new \RuntimeException(sprintf(
        'Class not available: %s',
        $class,
      ), 0, $e);
    }
    $rConstructor = $rClass->getConstructor();
    if ($rConstructor === NULL) {
      return new $class();
    }
    $parameters = $rConstructor->getParameters();
    try {
      $args = $this->buildArgs($parameters);
    }
    catch (ContainerExceptionInterface $e) {
      throw new \RuntimeException(sprintf(
        'Cannot find parameter values for %s: %s',
        $class,
        $e->getMessage(),
      ), 0, $e);
    }
    try {
      return new $class(...$args);
    }
    catch (\Throwable $e) {
      throw new \RuntimeException(sprintf(
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
   * @throws \Psr\Container\ContainerExceptionInterface
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
   * @throws \Psr\Container\ContainerExceptionInterface
   *   Failed to find a value for a parameter.
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
      $args[$i] = $this->findArgValue($parameter);
    }
    ksort($args);
    if ($this->variadicArgs) {
      $args = [...$args, ...$this->variadicArgs];
    }
    return $args;
  }

  /**
   * Finds an argument value for a parameter.
   *
   * @param \ReflectionParameter $parameter
   *   The parameter.
   *
   * @return mixed
   *   Argument value.
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  protected function findArgValue(\ReflectionParameter $parameter): mixed {
    $name = $parameter->getName();
    $type = ltrim((string) $parameter->getType(), '?');
    if (array_key_exists($name, $this->namedArgs)) {
      return $this->namedArgs[$name];
    }
    if ($type !== '' && array_key_exists($type, $this->typeArgs)) {
      return $this->typeArgs[$type];
    }
    foreach ($parameter->getAttributes(Autowire::class, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
      $attribute = $attribute->newInstance();
      assert($attribute instanceof Autowire);
      return $this->resolveAutowireValue($attribute->value);
    }
    if ($this->container->has($type . ' $' . $name)) {
      return $this->container->get($type . ' $' . $name);
    }
    if ($this->container->has($type)) {
      return $this->container->get($type);
    }
    throw new AutowiringFailedException($type . ' $' . $name, sprintf(
      'Cannot autowire parameter %s.',
      MessageUtil::formatReflector($parameter),
    ));
  }

  /**
   * Resolves a value for an autowire attribute.
   *
   * @param mixed $value
   *   Value from the autowire attribute.
   *
   * @return mixed
   *   Resolved value.
   *
   * @throws \Psr\Container\ContainerExceptionInterface
   */
  protected function resolveAutowireValue(mixed $value): mixed {
    if ($value === NULL) {
      return NULL;
    }
    if ($value instanceof Reference) {
      return $this->container->get($value->__toString());
    }
    if (is_object($value)) {
      throw new \LogicException(sprintf(
        'Unsupported autowire value type %s.',
        get_class($value),
      ));
    }
    if (\is_string($value)) {
      if (\preg_match('@%env\((.*)\)%@', $value, $matches)) {
        return \getenv($matches[1]);
      }
      if (\preg_match('@%(.*)%@', $value, $matches)) {
        return $this->container->getParameter($matches[1]);
      }
      throw new \LogicException(sprintf(
        'Unsupported autowire string %s.',
        $value,
      ));
    }
    if (\is_array($value)) {
      return \array_map(
        $this->resolveAutowireValue(...),
        $value,
      );
    }
    return $value;
  }

}
