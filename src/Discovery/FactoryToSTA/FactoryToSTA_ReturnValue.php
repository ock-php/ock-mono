<?php
declare(strict_types=1);

namespace Donquixote\Cf\Discovery\FactoryToSTA;

use Donquixote\Cf\SchemaToAnything\Partial\SchemaToAnythingPartialInterface;
use Donquixote\FactoryReflection\Factory\ReflectionFactory;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\FactoryReflection\FunctionToReturnType\FunctionToReturnTypeInterface;
use Donquixote\FactoryReflection\Util\FactoryUtil;
use Donquixote\ReflectionKit\ContextFinder\ContextFinderInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

/**
 * Decorator that also considers the return value as an STA.
 */
class FactoryToSTA_ReturnValue implements FactoryToSTAInterface {

  /**
   * @var \Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTAInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface
   */
  private $paramToValue;

  /**
   * @var \Donquixote\ReflectionKit\ContextFinder\ContextFinderInterface
   */
  private $contextFinder;

  /**
   * @var \Donquixote\FactoryReflection\FunctionToReturnType\FunctionToReturnTypeInterface
   */
  private $functionToReturnType;

  /**
   * @param \Donquixote\Cf\Discovery\FactoryToSTA\FactoryToSTAInterface $decorated
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   * @param \Donquixote\ReflectionKit\ContextFinder\ContextFinderInterface $contextFinder
   * @param \Donquixote\FactoryReflection\FunctionToReturnType\FunctionToReturnTypeInterface $functionToReturnType
   */
  public function __construct(
    FactoryToSTAInterface $decorated,
    ParamToValueInterface $paramToValue,
    ContextFinderInterface $contextFinder,
    FunctionToReturnTypeInterface $functionToReturnType
  ) {
    $this->decorated = $decorated;
    $this->paramToValue = $paramToValue;
    $this->contextFinder = $contextFinder;
    $this->functionToReturnType = $functionToReturnType;
  }

  /**
   * {@inheritdoc}
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?SchemaToAnythingPartialInterface {

    if (NULL !== $sta = $this->decorated->factoryGetPartial($factory)) {
      return $sta;
    }

    $value = FactoryUtil::factoryInvokePTV(
      $factory,
      $this->paramToValue);

    if ($value instanceof SchemaToAnythingPartialInterface) {
      return $value;
    }

    if ($value instanceof ReflectionFactoryInterface) {
      $value->is_return = TRUE;
      $value->is_return_value = TRUE;
      return $this->decorated->factoryGetPartial($value);
    }

    if (!is_callable($value)) {
      return NULL;
    }

    $factory = ReflectionFactory::fromCallable(
      $value,
      $this->contextFinder,
      $this->functionToReturnType);

    if (NULL === $factory) {
      return NULL;
    }

    $factory->is_return = TRUE;
    $factory->is_return_callable = TRUE;
    return $this->decorated->factoryGetPartial($factory);
  }
}
