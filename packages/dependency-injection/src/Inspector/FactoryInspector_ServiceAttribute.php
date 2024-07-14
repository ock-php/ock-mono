<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\DependencyInjection\Attribute\Service;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Registers services for classes and methods with #[Service] attribute.
 *
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
 */
class FactoryInspector_ServiceAttribute implements FactoryInspectorInterface {

  use CreateDefinitionTrait;

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    $attributes = $reflector->getAttributeInstances(Service::class);
    if (!$attributes) {
      return;
    }
    if (!$reflector->isCallable()) {
      throw new MalformedDeclarationException(sprintf(
        'Attribute #[%s] is not allowed on %s: %s',
        \get_class($attributes[0]),
        $reflector->getDebugName(),
        match (true) {
          $reflector->isMethod() => 'The method must be callable.',
          default => 'The class must be instantiable',
        },
      ));
    }
    $class = $reflector->getReturnClassName();
    foreach ($attributes as $attribute) {
      // @todo Rename this to just `id`.
      $id = $attribute->serviceId;
      if ($id === null) {
        if ($class === null) {
          throw new MalformedDeclarationException(sprintf(
            'Attribute #[%s] is not allowed on %s: The return type must be a single class name, or an id must be provided.',
            \get_class($attributes[0]),
            $reflector->getDebugName(),
          ));
        }
        if ($class === \Closure::class) {
          // Use a more specific service id.
          // @todo Also rethink service id for other builtin classes.
          $id = $reflector->getDebugName();
        }
        else {
          $id = $class;
        }
      }

      // @todo Rethink or drop the suffix.
      if ($attribute->serviceIdSuffix !== null) {
        $id .= $attribute->serviceIdSuffix;
      }
      // @todo Consider to only support one of the properties.
      if ($attribute->target !== null) {
        $id .= ' $' . $attribute->target;
      }
      // Create a new definition each time, so that they can be updated
      // independently later.
      $definition = $this->createDefinition($class, true);
      if ($method = $reflector->getMethodName()) {
        if ($reflector->isStatic()) {
          $definition->setFactory([$reflector->getClassName(), $method]);
        }
        else {
          // Assume that the class name is the service id.
          $definition->setFactory([new Reference($reflector->getClassName()), $method]);
        }
      }
      yield $id => $definition;
    }
  }

}
