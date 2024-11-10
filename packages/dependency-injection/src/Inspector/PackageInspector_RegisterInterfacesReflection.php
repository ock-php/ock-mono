<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\PackageInspectorInterface;
use Ock\ClassDiscovery\ReflectionClassesIA\ReflectionClassesIAInterface;
use Symfony\Component\Config\Resource\ClassExistenceResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Registers interface reflectors in the ContainerBuilder.
 *
 * This is needed to bypass the bug reported in
 * https://github.com/symfony/symfony/issues/58821.
 */
class PackageInspector_RegisterInterfacesReflection implements PackageInspectorInterface {

  /**
   * Constructor.
   */
  public function __construct(
    private readonly PackageInspectorInterface $decorated,
  ) {}

  /**
   * Applies this decorator if it is needed.
   *
   * @param \Ock\ClassDiscovery\Inspector\PackageInspectorInterface $decorated
   *
   * @return \Ock\ClassDiscovery\Inspector\PackageInspectorInterface
   */
  public static function decorateIfNeeded(PackageInspectorInterface $decorated): PackageInspectorInterface {
    if (!static::isNeeded()) {
      return $decorated;
    }
    return static::create($decorated);
  }

  /**
   * Detects if this workaround is needed.
   *
   * @return bool
   */
  public static function isNeeded(): bool {
    if (class_exists(ClassExistenceResource::class)) {
      // The 'symfony/config' package is present, so the bug does not occur.
      return false;
    }
    // Do some feature detection.
    $container = new ContainerBuilder();
    if ($container->getReflectionClass(PackageInspectorInterface::class)) {
      // This seems to be a version of symfony where the bug is already fixed.
      return false;
    }
    return true;
  }

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
   * Merges the class reflectors from another container.
   */
  public static function mergeFromOtherContainer(ContainerBuilder $target, ContainerBuilder $source): void {
    if (!static::isNeeded()) {
      return;
    }
    $containerReflectionClass = new \ReflectionClass(ContainerBuilder::class);
    if (!$containerReflectionClass->hasProperty('classReflectors')) {
      // This seems to be a different version of symfony.
      return;
    }
    // Manipulate the '->classReflectors' property, to bypass the problem
    // https://github.com/symfony/symfony/issues/58821
    $property = $containerReflectionClass->getProperty('classReflectors');
    $reflectors = [];
    if ($property->isInitialized($source)) {
      $reflectors = $property->getValue($source);
    }
    if (!$reflectors) {
      return;
    }
    if ($property->isInitialized($target)) {
      $reflectors = $property->getValue($target) + $reflectors;
    }
    $property->setValue($target, $reflectors);
  }

  /**
   * {@inheritdoc}
   */
  public function findInPackage(ReflectionClassesIAInterface $package): \Iterator {
    $interfaces = [];
    foreach ($this->decorated->findInPackage($package) as $key => $fact) {
      yield $key => $fact;
      if ($fact instanceof Definition) {
        $class = $fact->getClass();
        if ($class !== null && interface_exists($class)) {
          $interfaces[] = $class;
        }
      }
    }

    if (!$interfaces) {
      return;
    }

    yield 'register interfaces' => function (ContainerBuilder $container) use ($interfaces): void {
      if ($container->isTrackingResources()) {
        // The trick is not needed, or might even have bad side effects.
        return;
      }
      $containerReflectionClass = new \ReflectionClass($container);
      if (!$containerReflectionClass->hasProperty('classReflectors')) {
        // This seems to be a different version of symfony.
        return;
      }
      // Manipulate the '->classReflectors' property, to bypass the problem
      // https://github.com/symfony/symfony/issues/58821
      $property = $containerReflectionClass->getProperty('classReflectors');
      $reflectors = array_map(
        fn (string $interface) => new \ReflectionClass($interface),
        array_combine($interfaces, $interfaces),
      );
      if ($property->isInitialized($container)) {
        $reflectors = $property->getValue($container) + $reflectors;
      }
      $property->setValue($container, $reflectors);
    };
  }

}
