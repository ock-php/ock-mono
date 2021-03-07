<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Discovery\FactoryToSTA;

use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface;
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
   * @var \Donquixote\OCUI\Discovery\FactoryToSTA\FactoryToSTAInterface
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
   * @param \Donquixote\OCUI\Discovery\FactoryToSTA\FactoryToSTAInterface $decorated
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
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?FormulaToAnythingPartialInterface {

    if (NULL !== $sta = $this->decorated->factoryGetPartial($factory)) {
      return $sta;
    }

    $value = FactoryUtil::factoryInvokePTV(
      $factory,
      $this->paramToValue);

    if ($value === NULL) {
      // Cannot match all parameters.
      return NULL;
    }

    if ($value instanceof FormulaToAnythingPartialInterface) {
      return $value;
    }

    if ($value instanceof ReflectionFactoryInterface) {
      return $this->decorated->factoryGetPartial($value);
    }

    if (!is_callable($value)) {
      return NULL;
    }

    try {
      $factory = ReflectionFactory::fromCallable(
        $value,
        $this->contextFinder,
        $this->functionToReturnType);
    }
    catch (\ReflectionException $e) {
      throw new \RuntimeException('Impossible exception', 0, $e);
    }
    catch (\Exception $e) {
      throw new \RuntimeException('Impossible exception', 0, $e);
    }

    if (NULL === $factory) {
      return NULL;
    }

    return $this->decorated->factoryGetPartial($factory);
  }
}
