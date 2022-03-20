<?php

declare(strict_types=1);

namespace Donquixote\Ock\FindIn\Factory;

use Donquixote\Ock\Attribute\Incarnator\OckIncarnator;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartial_Callable;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartial_Class;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface;
use Donquixote\Ock\MetadataList\MetadataListInterface;
use Donquixote\Ock\Util\MessageUtil;
use Donquixote\Ock\Util\ReflectionUtil;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

/**
 * @template-implements FindInAnnotatedFactoryInterface<int, IncarnatorPartialInterface>
 */
class FindInAnnotatedFactory_IncarnatorPartial implements FindInAnnotatedFactoryInterface {

  /**
   * @var \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface|null
   */
  private ?ParamToValueInterface $paramToValue;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface|null $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue = NULL) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * {@inheritdoc}
   */
  public function findInAnnotatedClass(\ReflectionClass $reflectionClass, MetadataListInterface $metadata): \Iterator {
    if (!$metadata->count(OckIncarnator::class)) {
      return;
    }
    $constructor = $reflectionClass->getConstructor();
    if (!$reflectionClass->isSubclassOf(IncarnatorPartialInterface::class)) {
      yield IncarnatorPartial_Class::fromClass($reflectionClass, $this->paramToValue);
      return;
    }

    if (!$constructor || !$params = $constructor->getParameters()) {
      /** @var IncarnatorPartialInterface $object */
      $object = $reflectionClass->newInstance();
    }
    else {
      $args = ReflectionUtil::paramsDemandValues($params, $this->paramToValue);
      /** @var IncarnatorPartialInterface $object */
      $object = $reflectionClass->newInstanceArgs($args);
    }

    yield $object;
  }

  /**
   * {@inheritdoc}
   */
  public function findInAnnotatedMethod(\ReflectionMethod $reflectionMethod, MetadataListInterface $metadata): \Iterator {
    if (!$metadata->count(OckIncarnator::class)) {
      return;
    }
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

    if (!\is_a($class, IncarnatorPartialInterface::class, TRUE)) {
      yield IncarnatorPartial_Callable::fromStaticMethod($reflectionMethod, $this->paramToValue);
      return;
    }

    if (!$params = $reflectionMethod->getParameters()) {
      $candidate = $reflectionMethod->invoke(NULL);
    }
    else {
      $args = ReflectionUtil::paramsGetValues($params, $this->paramToValue);
      $candidate = $reflectionMethod->invokeArgs(NULL, $args);
    }

    if ($candidate === NULL) {
      return;
    }

    if (!$candidate instanceof IncarnatorPartialInterface) {
      throw new \Exception(
        MessageUtil::expectedButFound(
          IncarnatorPartialInterface::class,
          $candidate));
    }

    yield $candidate;
  }

}
