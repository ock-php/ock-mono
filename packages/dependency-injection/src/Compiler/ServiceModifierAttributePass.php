<?php

namespace Ock\DependencyInjection\Compiler;

use Ock\ClassDiscovery\Reflection\ClassReflection;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\ClassDiscovery\Reflection\MethodReflection;
use Ock\DependencyInjection\Attribute\ServiceModifierInterface;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;
use Symfony\Component\DependencyInjection\Definition;
use function Ock\Helpers\is_valid_qcn;

/**
 * @see \Symfony\Component\DependencyInjection\Compiler\AttributeAutoconfigurationPass
 * @see \Ock\DependencyInjection\Attribute\ServiceModifierInterface
 */
final class ServiceModifierAttributePass extends AbstractRecursivePass {

  /**
   * {@inheritdoc}
   */
  protected function processValue(mixed $value, bool $isRoot = false): mixed {
    if ($value instanceof Definition
      && $value->isAutoconfigured()
      && !$value->isAbstract()
      && !$value->hasTag('container.ignore_attributes')
    ) {
      $this->processDefinition($value);
    }
    return parent::processValue($value, $isRoot);
  }

  /**
   * Alters a definition if it has a ServiceModifierInterface attribute.
   *
   * @param \Symfony\Component\DependencyInjection\Definition $definition
   */
  protected function processDefinition(Definition $definition): void {
    $reflector = $this->getFactoryReflection($definition);
    if ($reflector === null) {
      return;
    }
    $attributes = $reflector->getAttributeInstances(ServiceModifierInterface::class);
    if (!$attributes) {
      return;
    }
    foreach ($attributes as $attribute) {
      $attribute->modify($definition);
    }
  }

  /**
   * Gets a factory reflector for a service definition.
   *
   * @param \Symfony\Component\DependencyInjection\Definition $definition
   *
   * @return \Ock\ClassDiscovery\Reflection\FactoryReflectionInterface|null
   */
  protected function getFactoryReflection(Definition $definition): ?FactoryReflectionInterface {
    if ($factory = $definition->getFactory()) {
      if (!\is_array($factory)
        || \array_keys($factory) !== [0, 1]
      ) {
        // Whatever this is, it is not supported for now.
        return null;
      }
      [$class_or_reference, $method] = $factory;
      if (!\is_string($class_or_reference)
        || !is_valid_qcn($class_or_reference)
      ) {
        // Not supported for now.
        return null;
      }
      return new MethodReflection($class_or_reference, $method);
    }
    if ($class = $definition->getClass()) {
      if (!is_valid_qcn($class)) {
        // Not supported for now.
        return null;
      }
      return new ClassReflection($class);
    }
    // Whatever this is, it is not supported.
    return null;
  }

}
