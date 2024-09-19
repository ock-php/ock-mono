<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Inspector;

/**
 * Static factories for to create a class inspector.
 */
class FactoryInspector {

  /**
   * Creates a new instance from a list of inspector candidates.
   *
   * @param iterable<mixed> $candidates
   *   List of objects that may or may not be inspectors.
   *   This accepts any iterable, to support symfony tagged services.
   *
   * @return \Ock\ClassDiscovery\Inspector\FactoryInspectorInterface
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates): FactoryInspectorInterface {
    $classInspector = FactoryInspector_Concat::fromCandidateObjects($candidates);
    $classInspector = self::applyDecorators($classInspector, $candidates);
    return $classInspector;
  }

  /**
   * @param \Ock\ClassDiscovery\Inspector\FactoryInspectorInterface $decorated
   * @param iterable<mixed> $candidates
   *   Objects which may or may not contain decorator closures.
   *
   * @return \Ock\ClassDiscovery\Inspector\FactoryInspectorInterface
   */
  public static function applyDecorators(FactoryInspectorInterface $decorated, iterable $candidates): FactoryInspectorInterface {
    foreach ($candidates as $candidate) {
      if (!$candidate instanceof \Closure) {
        continue;
      }
      $rf = new \ReflectionFunction($candidate);
      $params = $rf->getParameters();
      if (count($params) !== 1) {
        continue;
      }
      $type = $params[0]->getType();
      if (!$type) {
        continue;
      }
      if ($type->__toString() !== FactoryInspectorInterface::class) {
        continue;
      }
      $decorator = $candidate($decorated);
      if (!$decorator instanceof FactoryInspectorInterface) {
        continue;
      }
      $decorated = $decorator;
    }
    return $decorated;
  }

}
