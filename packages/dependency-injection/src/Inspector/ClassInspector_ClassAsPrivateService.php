<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\ClassInspectorInterface;
use Ock\DependencyInjection\ServiceDefinitionUtil;
use Ock\Reflection\ClassReflection;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
 */
class ClassInspector_ClassAsPrivateService implements ClassInspectorInterface {

  use CreateDefinitionTrait;

  /**
   * {@inheritdoc}
   */
  public static function create(): static {
    $definition = new Definition();
    $definition->setAutoconfigured(true);
    $definition->setAutowired(true);
    $definition->addTag(ServiceDefinitionUtil::TENTATIVE_TAG);
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static(
      fn () => clone $definition,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    if (!$classReflection->isInstantiable()) {
      // This could be an interface, a trait, or an abstract class, or a class
      // with private constructor.
      return;
    }
    $class = $classReflection->getName();
    // It must be a class.
    yield $class => $this->createDefinition($class);
  }

}
