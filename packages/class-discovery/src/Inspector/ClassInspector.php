<?php

declare(strict_types=1);

namespace Ock\ClassDiscovery\Inspector;

/**
 * Static factories for to create a class inspector.
 */
class ClassInspector {

  /**
   * Creates a new instance from a list of inspector candidates.
   *
   * @param array $candidates
   *   List of objects that may or may not be inspectors.
   *   This accepts any iterable, to support symfony tagged services.
   * @param bool $includeFactoryInspectors
   *   TRUE to also include factory inspectors.
   *
   * @return \Ock\ClassDiscovery\Inspector\ClassInspectorInterface
   *   New instance.
   */
  public static function fromCandidateObjects(iterable $candidates, bool $includeFactoryInspectors): ClassInspectorInterface {
    $classInspector = ClassInspector_Concat::fromCandidateObjects($candidates, false);
    $classInspector = self::applyDecorators($classInspector, $candidates);
    if ($includeFactoryInspectors) {
      $factoryInspector = FactoryInspector::fromCandidateObjects($candidates);
      if (!$factoryInspector instanceof FactoryInspector_Concat
        || !$factoryInspector->isEmpty()
      ) {
        $classInspector = new ClassInspector_Concat([
          $classInspector,
          new ClassInspector_Factories($factoryInspector),
        ]);
      }
    }
    return $classInspector;
  }

  /**
   * @param \Ock\ClassDiscovery\Inspector\ClassInspectorInterface $decorated
   * @param iterable $candidates
   *
   * @return \Ock\ClassDiscovery\Inspector\ClassInspectorInterface
   */
  public static function applyDecorators(ClassInspectorInterface $decorated, iterable $candidates): ClassInspectorInterface {
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
      if ($type->__toString() !== ClassInspectorInterface::class) {
        continue;
      }
      $decorator = $candidate($decorated);
      if (!$decorator instanceof ClassInspectorInterface) {
        continue;
      }
      $decorated = $decorator;
    }
    return $decorated;
  }

}
