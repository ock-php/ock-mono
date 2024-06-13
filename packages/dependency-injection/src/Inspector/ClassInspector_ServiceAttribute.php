<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Inspector\ClassInspectorInterface;
use Ock\ClassDiscovery\Reflection\ClassReflection;
use Ock\DependencyInjection\Attribute\Service;

/**
 * Registers services for classes with #[Service] attribute.
 *
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
 */
class ClassInspector_ServiceAttribute implements ClassInspectorInterface {

  use CreateDefinitionTrait;

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    $attributes = $classReflection->getAttributeInstances(Service::class);
    if (!$attributes) {
      return;
    }
    if (!$classReflection->isInstantiable()) {
      throw new MalformedDeclarationException(sprintf(
        'Attribute #[%s] is not allowed on %s: The class must be instantiable.',
        \get_class($attributes[0]),
        $classReflection->name,
      ));
    }
    foreach ($attributes as $attribute) {
      // @todo Rename this to just `id`.
      $id = $attribute->serviceId ?? $classReflection->name;
      // @todo Rethink or drop the suffix.
      if ($attribute->serviceIdSuffix !== null) {
        $id .= $attribute->serviceIdSuffix;
      }
      if ($attribute->target !== null) {
        $id .= ' $' . $attribute->target;
      }
      // Create a new definition each time, so that they can be updated
      // independently later.
      yield $id => $this->createDefinition($classReflection->name, true);
    }
  }

}
