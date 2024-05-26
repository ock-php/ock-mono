<?php

declare(strict_types=1);

namespace Ock\DID\Inspector;

use Ock\ClassDiscovery\Exception\MalformedDeclarationException;
use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\ClassDiscovery\Util\AttributesUtil;
use Ock\DID\Attribute\Service;
use Ock\DID\Attribute\ServiceDiscoveryUtil;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @template-implements FactoryInspectorInterface<\Symfony\Component\DependencyInjection\Definition|string>
 *
 * @see \Ock\DID\Attribute\Service
 */
class FactoryInspector_SymfonyServiceDefinition_ServiceAttribute implements FactoryInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Closure(): \Symfony\Component\DependencyInjection\Definition $createNewDefinition
   *   Closure to create a new service definition.
   */
  public function __construct(
    private readonly \Closure $createNewDefinition,
  ) {}

  /**
   * Creates a new instance.
   *
   * This needs to be re-implemented in a subclass, if the constructor signature
   * changes.
   *
   * @param \Symfony\Component\DependencyInjection\Definition|null $prototype
   *   Prototype service definition, or NULL for a default definition.
   *
   * @return static
   *   New instance.
   */
  public static function create(Definition $prototype = null): self {
    if ($prototype === NULL) {
      // Default prototype.
      $prototype = new Definition();
      $prototype->setAutoconfigured(TRUE);
      $prototype->setAutowired(TRUE);
      $createNewDefinition = static fn (): Definition => clone $prototype;
    }
    else {
      $createNewDefinition = static::createDeepCloneClosure($prototype);
    }
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static($createNewDefinition);
  }

  /**
   * Creates a closure to deep-clone the definition.
   *
   * This code is mostly extracted from 'symfony/dependency-injection' package.
   *
   * @param \Symfony\Component\DependencyInjection\Definition $prototype
   *   Prototype service definition.
   *
   * @return \Closure(): \Symfony\Component\DependencyInjection\Definition
   *   Closure to create a deep clone of the definition.
   *
   * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
   */
  public static function createDeepCloneClosure(Definition $prototype): \Closure {
    $serialized = serialize($prototype);

    if (!strpos($serialized, 'O:48:"Symfony\Component\DependencyInjection\Definition"', 55)
      && !strpos($serialized, 'O:53:"Symfony\Component\DependencyInjection\ChildDefinition"', 55)
    ) {
      // No nested definitions - no deep cloning.
      return static fn () => clone $prototype;
    }

    // Prepare for deep cloning.
    // @todo Follow upstream changes to this cloning process.
    $closure = static fn (): Definition => clone $prototype;
    foreach ([
      'Arguments',
      'Properties',
      'MethodCalls',
      'Configurator',
      'Factory',
      'Bindings'
    ] as $key) {
      $serialized = serialize($prototype->{'get' . $key}());

      if (strpos($serialized, 'O:48:"Symfony\Component\DependencyInjection\Definition"')
        || strpos($serialized, 'O:53:"Symfony\Component\DependencyInjection\ChildDefinition"')
      ) {
        $closure = static fn(): Definition => $closure()->{'set' . $key}(unserialize($serialized));
      }
    }

    return $closure;
  }

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    $attributes = AttributesUtil::getAll($reflector, Service::class);
    if (!$attributes) {
      return;
    }
    if (!$reflector->isCallable()) {
      throw new MalformedDeclarationException(sprintf(
        'Attribute %s is not allowed on %s.',
        \get_class($attributes[0]),
        $reflector->getDebugName(),
      ));
    }
    $originalClassName = $reflector->getClassName();
    try {
      $returnClass = $reflector->getReturnClass();
    }
    catch (\ReflectionException $e) {
      // Silently skip this factory.
      // @todo Record an error in a service.
      return;
    }
    if ($returnClass === NULL) {
      throw new MalformedDeclarationException(sprintf(
        'Expected a class-like return type on %s.',
        $reflector->getDebugName(),
      ));
    }
    $returnClassName = $returnClass->name;
    $methodName = $reflector->getMethodName();
    foreach ($attributes as $attribute) {
      $definition = ($this->createNewDefinition)();
      assert($definition instanceof Definition);
      $definition->setClass($returnClassName);
      if ($methodName !== NULL) {
        // This is a method.
        if ($reflector->isStatic()) {
          if ($originalClassName === $returnClassName) {
            // Use the short-hand definition.
            $definition->setFactory(['~', $methodName]);
          }
          else {
            $definition->setFactory([$originalClassName, $methodName]);
          }
        }
        else {
          // Method is not static. We have to assume this is a service.
          // For now, assume that the class name is the service id.
          // @todo Make this more sophisticated.
          $thisReference = new Reference($reflector->getClassName());
          $definition->setFactory([$thisReference, $reflector->isMethod()]);
        }
      }
      if ($attribute->serviceId !== null) {
        $id = $attribute->serviceId;
      }
      elseif ($attribute->serviceIdSuffix !== null) {
        $id = $returnClassName . $attribute->serviceIdSuffix;
      }
      else {
        $id = $returnClassName;
      }
      // Do not set parameters.
      // Instead, rely on the AutowirePass.
      yield $id => $definition;

      if ($attribute->alias === FALSE) {
        // Don't register any aliases.
        return;
      }

      if (is_string($attribute->alias)) {
        $aliases = [$attribute->alias];
      }
      elseif (\is_array($attribute->alias)) {
        $aliases = $attribute->alias;
      }
      else {
        // Determine aliases based on interfaces.
        $alias = ServiceDiscoveryUtil::classGetTypeName($returnClass);
        if ($attribute->serviceIdSuffix !== null) {
          $alias .= $attribute->serviceIdSuffix;
        }
        $aliases = [$alias];
      }

      foreach ($aliases as $alias) {
        if ($alias !== $id) {
          // Yield aliases as string -> string mappings.
          yield $alias => $id;
        }
      }
    }
  }

}
