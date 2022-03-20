<?php

declare(strict_types=1);

namespace Donquixote\Ock\Attribute\Incarnator;

use Donquixote\Ock\IncarnatorPartial\IncarnatorPartial_Callable;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartial_Class;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface;
use Donquixote\Ock\Util\MessageUtil;
use Donquixote\Ock\Util\ReflectionUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class OckIncarnator {

  /**
   * @param \ReflectionClass $reflectionClass
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface
   *
   * @throws \Exception
   */
  public function classGetPartial(\ReflectionClass $reflectionClass, ParamToValueInterface $paramToValue): IncarnatorPartialInterface {
    $constructor = $reflectionClass->getConstructor();
    if (!$reflectionClass->isSubclassOf(IncarnatorPartialInterface::class)) {
      return IncarnatorPartial_Class::fromClass($reflectionClass, $paramToValue);
    }

    if (!$constructor || !$params = $constructor->getParameters()) {
      /** @var IncarnatorPartialInterface $object */
      $object = $reflectionClass->newInstance();
    }
    else {
      $args = ReflectionUtil::paramsGetValues($params, $paramToValue);
      /** @var IncarnatorPartialInterface $object */
      $object = $reflectionClass->newInstanceArgs($args);
    }
    return $object;
  }

  /**
   * @param \ReflectionMethod $reflectionMethod
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface
   *
   * @throws \Exception
   */
  public function methodGetPartial(\ReflectionMethod $reflectionMethod, ParamToValueInterface $paramToValue): IncarnatorPartialInterface {
    if (!$reflectionMethod->isStatic()) {
      throw new \Exception('Method must be static.');
    }
    $rtype = $reflectionMethod->getReturnType();
    if (!$rtype) {
      throw new \Exception('Method must have a return type declaration.');
    }
    if (!$rtype instanceof \ReflectionNamedType || $rtype->isBuiltin()) {
      throw new \Exception('Method return type must be a single class.');
    }
    /** @var class-string|'self'|'static' $class */
    $class = $rtype->getName();
    if ($class === 'self' || $class === 'static') {
      $class = $reflectionMethod->getDeclaringClass()->getName();
    }

    if (!is_a($class, IncarnatorPartialInterface::class, TRUE)) {
      return IncarnatorPartial_Callable::fromStaticMethod($reflectionMethod, $paramToValue);
    }

    if (!$params = $reflectionMethod->getParameters()) {
      $candidate = $reflectionMethod->invoke(NULL);
    }
    else {
      $args = ReflectionUtil::paramsGetValues($params, $paramToValue);
      $candidate = $reflectionMethod->invokeArgs(NULL, $args);
    }

    if (!$candidate instanceof IncarnatorPartialInterface) {
      throw new \Exception(
        MessageUtil::expectedButFound(
          IncarnatorPartialInterface::class,
          $candidate));
    }

    return $candidate;
  }

}
