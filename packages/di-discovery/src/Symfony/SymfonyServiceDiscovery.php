<?php

declare(strict_types=1);

namespace Ock\DID\Symfony;

use Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface;
use Ock\ClassDiscovery\Discovery\FactoryDiscovery;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIA_ClassFilesIA;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Ock\DID\Inspector\FactoryInspector_SymfonyServiceDefinition_ServiceAttribute;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class SymfonyServiceDiscovery {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\FactoryInspectorInterface<\Symfony\Component\DependencyInjection\Definition|string> $inspector
   *   Inspector that finds service definitions.
   */
  public function __construct(
    private readonly FactoryInspectorInterface $inspector,
  ) {}

  /**
   * Creates a new instance.
   *
   * @param \Symfony\Component\DependencyInjection\Definition|null $prototype
   *   Prototype service definition, or NULL for a default definition.
   *
   * @return static
   *   New instance.
   */
  public static function create(Definition $prototype = null): static {
    $inspector = FactoryInspector_SymfonyServiceDefinition_ServiceAttribute::create($prototype);
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static($inspector);
  }

  /**
   * Discovers service definitions in class files.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $builder
   *   Container builder.
   * @param \Ock\ClassDiscovery\ClassFilesIA\ClassFilesIAInterface|\Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface $classes
   *   Classes to search.
   */
  public function discover(ContainerBuilder $builder, ClassFilesIAInterface|ReflectionClassesIAInterface $classes): void {
    if (!$classes instanceof ReflectionClassesIAInterface) {
      $classes = new ReflectionClassesIA_ClassFilesIA($classes);
    }
    $declarations = new FactoryDiscovery($classes, $this->inspector);
    $this->register($builder, $declarations);
  }

  /**
   * Registers service definitions and aliases.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerBuilder $builder
   *   Container builder.
   * @param iterable<string, string|\Symfony\Component\DependencyInjection\Definition> $declarations
   *   Service definitions and aliases.
   */
  protected function register(ContainerBuilder $builder, iterable $declarations): void {
    $aliases = [];
    $definitions = [];
    foreach ($declarations as $id => $declaration) {
      if ($declaration instanceof Definition) {
        $definitions[$id] = $declaration;
      }
      elseif (is_string($declaration)) {
        $aliases[$id] = $declaration;
      }
    }
    $aliases = \array_diff_key($aliases, $definitions);
    $builder->addDefinitions($definitions);
    $builder->addAliases($aliases);
  }

}
