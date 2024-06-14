<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\PackageInspectorInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Registers aliases for singly implemented interfaces.
 */
class PackageInspector_SinglyImplementedInterfaceAliasDecorator implements PackageInspectorInterface {

  /**
   * Constructor.
   */
  public function __construct(
    private readonly PackageInspectorInterface $decorated,
  ) {}

  /**
   * @param \Ock\ClassDiscovery\Inspector\PackageInspectorInterface $decorated
   *
   * @return static
   */
  public static function create(PackageInspectorInterface $decorated): static {
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static($decorated);
  }

  /**
   * {@inheritdoc}
   */
  public function findInPackage(ReflectionClassesIAInterface $package): \Iterator {
    $service_classes = [];
    foreach ($this->decorated->findInPackage($package) as $key => $fact) {
      yield $key => $fact;
      if (!$fact instanceof Definition
        || $fact->getFactory()
        || $fact->getClass() !== $key
      ) {
        continue;
      }
      $service_classes[$key] = $key;
    }

    $package_interfaces = [];
    $classes_by_interface = [];
    foreach ($package as $reflector) {
      if ($reflector->isInterface()) {
        $package_interfaces[] = $reflector->name;
      }
      elseif ($reflector->isClass()
        && $reflector->isInstantiable()
        && isset($service_classes[$reflector->name])
      ) {
        foreach ($reflector->getInterfaceNames() as $interface) {
          $classes_by_interface[$interface][] = $reflector->name;
        }
      }
    }

    $aliases = [];
    foreach ($package_interfaces as $interface) {
      $classes = $classes_by_interface[$interface] ?? [];
      if (count($classes) !== 1) {
        continue;
      }
      // The class is the only implementation within this package.
      $aliases[$interface] = $classes[0];
    }

    if (!$aliases) {
      return;
    }

    // Add a label as key for reporting purposes.
    $label = '# Register aliases for singly implemented interfaces.';
    yield $label => static function (ContainerBuilder $container) use ($aliases): void {
      foreach ($aliases as $interface => $class) {
        // Don't overwrite existing aliases.
        if (!$container->hasAlias($interface)
          && !$container->hasDefinition($interface)
        ) {
          $container->setAlias($interface, $class);
        }
      }
    };
  }

}
