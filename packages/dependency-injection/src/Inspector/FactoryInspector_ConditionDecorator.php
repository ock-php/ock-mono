<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\FactoryInspectorInterface;
use Ock\ClassDiscovery\Reflection\FactoryReflectionInterface;
use Ock\DependencyInjection\Attribute\ServiceConditionAttributeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers services for classes and methods with #[Service] attribute.
 *
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
 */
class FactoryInspector_ConditionDecorator implements FactoryInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\FactoryInspectorInterface $decorated
   */
  public function __construct(
    private readonly FactoryInspectorInterface $decorated,
  ) {}

  /**
   * @param \Ock\ClassDiscovery\Inspector\PackageInspectorInterface $decorated
   *
   * @return static
   */
  public static function create(FactoryInspectorInterface $decorated): static {
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static($decorated);
  }

  /**
   * {@inheritdoc}
   */
  public function findInFactory(FactoryReflectionInterface $reflector): \Iterator {
    $attributes = $reflector->getAttributeInstances(ServiceConditionAttributeInterface::class);
    if ($attributes) {
      $result = null;
      yield 'check condition' => static function (ContainerBuilder $container) use (&$result, $attributes) {
        foreach ($attributes as $attribute) {
          if (!$attribute->check($container)) {
            $result = false;
            return;
          }
        }
        $result = true;
      };
      \assert($result !== null);
      if (!$result) {
        return;
      }
    }
    yield from $this->decorated->findInFactory($reflector);
  }

}
