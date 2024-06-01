<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Reflection;

use Ock\ClassDiscovery\Attribute\ReflectorAwareAttributeInterface;

/**
 * Trait with shared implementations.
 */
trait FactoryReflectionTrait {

  /**
   * {@inheritdoc}
   */
  public function hasRequiredParameters(): bool {
    $parameters = $this->getParameters();
    foreach ($parameters as $parameter) {
      if (!$parameter->isOptional()) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getAttributeInstances(string $name, int $flags = \ReflectionAttribute::IS_INSTANCEOF): array {
    $attributes = $this->getAttributes($name, $flags);
    if (!$attributes) {
      return [];
    }
    $instances = [];
    foreach ($attributes as $attribute) {
      $instance = $attribute->newInstance();
      if ($instance instanceof ReflectorAwareAttributeInterface) {
        $instance->setReflector($this);
      }
      $instances[] = $instance;
    }
    return $instances;
  }

}
