<?php

declare(strict_types=1);

namespace Ock\DependencyInjection;

use Ock\Reflection\ClassReflection;
use Ock\Reflection\MethodReflection;
use Symfony\Component\DependencyInjection\Definition;
use function Ock\Helpers\is_valid_qcn;

/**
 * Static methods related to service definitions.
 */
class ServiceDefinitionUtil {

  const TENTATIVE_TAG = 'tentative';

  /**
   * Gets a factory reflector for a service definition.
   *
   * @param \Symfony\Component\DependencyInjection\Definition $definition
   *   Service definition.
   *
   * @return \Ock\Reflection\ClassReflection|\Ock\Reflection\MethodReflection|null
   *   Class or method reflection, or NULL if not applicable.
   */
  public static function getFactoryReflection(Definition $definition): null|ClassReflection|MethodReflection {
    if ($factory = $definition->getFactory()) {
      if (!\is_array($factory)
        || \array_keys($factory) !== [0, 1]
      ) {
        // Whatever this is, it is not supported for now.
        return NULL;
      }
      [$class_or_reference, $method] = $factory;
      if (!\is_string($class_or_reference)
        || !is_valid_qcn($class_or_reference)
      ) {
        // Not supported for now.
        return NULL;
      }
      // Pretend that $class_or_reference is a known class, because the first
      // parameter expects class-string<T>.
      /** @var class-string $class_or_reference */
      return new MethodReflection($class_or_reference, $method);
    }
    if ($class = $definition->getClass()) {
      if (!is_valid_qcn($class)) {
        // Not supported for now.
        return NULL;
      }
      // Pretend that $class is a known class, because the first
      // parameter expects class-string<T>.
      /** @var class-string $class */
      return new ClassReflection($class);
    }
    // Whatever this is, it is not supported.
    return NULL;
  }

}
