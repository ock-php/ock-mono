<?php

namespace Ock\DependencyInjection\Compiler;

use Ock\DependencyInjection\Attribute\ServiceModifierInterface;
use Ock\DependencyInjection\ServiceDefinitionUtil;
use Symfony\Component\DependencyInjection\Compiler\AbstractRecursivePass;
use Symfony\Component\DependencyInjection\Definition;
use function Ock\ClassDiscovery\get_attributes;

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
    $reflector = ServiceDefinitionUtil::getFactoryReflection($definition);
    if ($reflector === null) {
      return;
    }
    $attributes = get_attributes($reflector, ServiceModifierInterface::class);
    if (!$attributes) {
      return;
    }
    foreach ($attributes as $attribute) {
      $attribute->modify($definition);
    }
  }

}
