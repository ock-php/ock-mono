<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Reflection;

use Ock\ClassDiscovery\Attribute\ReflectorAwareAttributeInterface;

trait AttributesHavingReflectionTrait {

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
