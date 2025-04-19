<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\ClassInspectorInterface;
use Ock\ClassDiscovery\Reflection\ClassReflection;
use Ock\DependencyInjection\Attribute\ServiceConditionAttributeInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use function Ock\ClassDiscovery\get_attributes;

/**
 * Registers services for classes and methods with #[Service] attribute.
 *
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
 */
class ClassInspector_ConditionDecorator implements ClassInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\ClassInspectorInterface $decorated
   */
  public function __construct(
    private readonly ClassInspectorInterface $decorated,
  ) {}

  /**
   * @param \Ock\ClassDiscovery\Inspector\ClassInspectorInterface $decorated
   *
   * @return static
   */
  public static function create(ClassInspectorInterface $decorated): static {
    // Extend at your own risk.
    // @phpstan-ignore-next-line
    return new static($decorated);
  }

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    $attributes = get_attributes($classReflection, ServiceConditionAttributeInterface::class);
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
    yield from $this->decorated->findInClass($classReflection);
  }

}
