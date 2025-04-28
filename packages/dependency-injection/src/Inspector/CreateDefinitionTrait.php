<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\Reflection\FactoryReflectionInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Shared methods for inspectors that create new definitions.
 *
 * @todo Replicate the prototype-cloning from symfony.
 */
trait CreateDefinitionTrait {

  /**
   * Constructor.
   *
   * @param \Closure(): \Symfony\Component\DependencyInjection\Definition $createNewDefinition
   */
  private function __construct(
    private readonly \Closure $createNewDefinition,
  ) {}

  /**
   * Creates a new instance with a default definition.
   */
  public static function create(): static {
    $definition = new Definition();
    $definition->setAutoconfigured(true);
    $definition->setAutowired(true);
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static(
      fn () => clone $definition,
    );
  }

  /**
   * Creates a new definition.
   *
   * @param class-string|null $class
   *   Class name.
   *
   * @return \Symfony\Component\DependencyInjection\Definition
   *   New service definition.
   */
  protected function createDefinition(string $class = null, bool $public = false): Definition {
    $definition = ($this->createNewDefinition)();
    if ($class !== null) {
      $definition->setClass($class);
    }
    if ($public) {
      $definition->setPublic(true);
    }
    return $definition;
  }

  /**
   * @param \Ock\Reflection\FactoryReflectionInterface $reflector
   * @param bool $public
   *
   * @return \Symfony\Component\DependencyInjection\Definition
   */
  protected function createDefinitionForFactory(FactoryReflectionInterface $reflector, bool $public = false): Definition {
    $class = $reflector->getReturnClassName()
      ?? throw new MalformedDeclarationException(sprintf(
        'Cannot create service for %s: The return type must be a single class name, or an id must be provided.',
        $reflector->getDebugName(),
      ));
    $definition = $this->createDefinition($class, $public);
    if ($method = $reflector->getMethodName()) {
      if ($reflector->isStatic()) {
        $definition->setFactory([$reflector->getClassName(), $method]);
      }
      else {
        // Assume that the class name is the service id.
        $definition->setFactory([new Reference($reflector->getClassName()), $method]);
      }
    }
    return $definition;
  }

}
