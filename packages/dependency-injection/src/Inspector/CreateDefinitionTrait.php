<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Symfony\Component\DependencyInjection\Definition;

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

}
