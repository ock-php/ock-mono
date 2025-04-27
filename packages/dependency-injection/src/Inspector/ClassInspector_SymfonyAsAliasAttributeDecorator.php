<?php

declare(strict_types=1);

namespace Ock\DependencyInjection\Inspector;

use Ock\ClassDiscovery\Inspector\ClassInspectorInterface;
use Ock\Reflection\ClassReflection;
use Ock\Helpers\Util\MessageUtil;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use function Ock\ClassDiscovery\get_attributes;

/**
 * Decorator to support the AsAlias attribute.
 *
 * @see \Symfony\Component\DependencyInjection\Loader\FileLoader::registerClasses()
 */
class ClassInspector_SymfonyAsAliasAttributeDecorator implements ClassInspectorInterface {

  /**
   * Constructor.
   *
   * @param \Ock\ClassDiscovery\Inspector\ClassInspectorInterface $decorated
   */
  public function __construct(
    private readonly ClassInspectorInterface $decorated,
  ) {}

  /**
   * Static factory that is easy to turn into a closure.
   *
   * @param \Ock\ClassDiscovery\Inspector\ClassInspectorInterface $decorated
   *
   * @return self
   */
  public static function create(ClassInspectorInterface $decorated): self {
    return new self($decorated);
  }

  /**
   * {@inheritdoc}
   */
  public function findInClass(ClassReflection $classReflection): \Iterator {
    $attributes = get_attributes($classReflection, AsAlias::class);
    if (!$attributes) {
      yield from $this->decorated->findInClass($classReflection);
      return;
    }
    $defaultAlias = $classReflection->getOnlyInterfaceName();
    foreach ($this->decorated->findInClass($classReflection) as $key => $fact) {
      if ($fact instanceof Definition) {
        if (!is_string($key)) {
          throw new \RuntimeException(
            sprintf(
              "Expected a string key with a service definition, found %s.",
              // In an iterator, the key could be anything.
              MessageUtil::formatValue($key),
            )
          );
        }
        foreach ($attributes as $attribute) {
          $alias = $attribute->id ?? $defaultAlias;
          if ($alias === null) {
            // Throw the same exception as in symfony FileLoader.
            throw new LogicException(sprintf(
              'Alias cannot be automatically determined for class "%s". If you have used the #[AsAlias] attribute with a class implementing multiple interfaces, add the interface you want to alias to the first parameter of #[AsAlias].',
              $classReflection->name,
            ));
          }
          $public = $attribute->public;
          // @todo Detect alias collisions on package level.
          yield $alias => new Alias($key, $public);
        }
      }
      yield $key => $fact;
    }
  }

}
