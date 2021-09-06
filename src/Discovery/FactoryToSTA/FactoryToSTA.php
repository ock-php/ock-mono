<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Discovery\FactoryToSTA;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Callback\CallbackReflection_Function;
use Donquixote\CallbackReflection\Callback\CallbackReflection_StaticMethod;
use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\ObCK\Core\Formula\Base\FormulaBaseInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Nursery\Cradle\Cradle_Callback;
use Donquixote\ObCK\Nursery\Cradle\Cradle_CallbackNoHelper;
use Donquixote\ObCK\Nursery\Cradle\CradleInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;
use Donquixote\ObCK\Util\ReflectionUtil;
use Donquixote\FactoryReflection\Factory\ReflectionFactoryInterface;
use Donquixote\FactoryReflection\FunctionToReturnType\FunctionToReturnTypeInterface;
use Donquixote\ReflectionKit\ContextFinder\ContextFinderInterface;
use Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface;

class FactoryToSTA implements FactoryToSTAInterface {

  /**
   * @var \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface|null
   */
  private $paramToValue;

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface $paramToValue
   * @param \Donquixote\ReflectionKit\ContextFinder\ContextFinderInterface $contextFinder
   * @param \Donquixote\FactoryReflection\FunctionToReturnType\FunctionToReturnTypeInterface $functionToReturnType
   *
   * @return \Donquixote\ObCK\Discovery\FactoryToSTA\FactoryToSTAInterface
   */
  public static function createComposite(ParamToValueInterface $paramToValue, ContextFinderInterface $contextFinder, FunctionToReturnTypeInterface $functionToReturnType) {
    return new FactoryToSTA_RequireAnnotationTag(
      new FactoryToSTA_ReturnValue(
        new self($paramToValue),
        $paramToValue,
        $contextFinder,
        $functionToReturnType));
  }

  /**
   * @param \Donquixote\ReflectionKit\ParamToValue\ParamToValueInterface|null $paramToValue
   */
  public function __construct(ParamToValueInterface $paramToValue = null) {
    $this->paramToValue = $paramToValue;
  }

  /**
   * {@inheritdoc}
   */
  public function factoryGetPartial(ReflectionFactoryInterface $factory): ?CradleInterface {

    $params = $factory->getParameters();

    // Require first parameter to exist and to have a class/interface type hint.
    if (0
      || !isset($params[0])
      || NULL === ($t0 = $params[0]->getClass())
    ) {
      return NULL;
    }
    unset($params[0]);

    // Require first parameter type hint class to be a Formula* object.
    if (FormulaInterface::class === $formulaType = $t0->getName()) {
      $formulaType = NULL;
      $specifity = -1;
    }
    // @todo Why are we only requiring FormulaBaseInterface?
    elseif (!is_a($formulaType, FormulaBaseInterface::class, TRUE)) {
      return NULL;
    }
    else {
      $specifity = \count($t0->getInterfaceNames());
    }

    // Look at second parameter, if exists.
    if (1
      && isset($params[1])
      && NULL !== ($t1 = $params[1]->getClass())
      && is_a(NurseryInterface::class, $t1->getName(), TRUE)
    ) {
      $hasStaParam = TRUE;
      unset($params[1]);
    }
    else {
      $hasStaParam = FALSE;
    }

    if (null === $callback = $this->reflectorGetCallback($factory->getReflector())) {
      return null;
    }

    // Look at remaining parameters.
    if ([] !== $params) {
      if (NULL === $boundArgs = ReflectionUtil::paramsGetValues($params, $this->paramToValue)) {
        // Some of the parameters cannot be bound.
        return NULL;
      }

      // Curry: Create a callback that integrates the new arg values.
      $callback = new CallbackReflection_BoundParameters($callback, $boundArgs);
    }

    if (null === $returnTypeClass = $factory->getReturnTypeClass()) {
      return null;
    }

    if ($hasStaParam) {
      $sta = new Cradle_Callback(
        $callback,
        $formulaType,
        $returnTypeClass->getName());
    }
    else {
      $sta = new Cradle_CallbackNoHelper(
        $callback,
        $formulaType,
        $returnTypeClass->getName());
    }

    $sta = $sta->withSpecifity($specifity);

    return $sta;
  }

  /**
   * @param \Reflector $reflector
   *
   * @return \Donquixote\CallbackReflection\Callback\CallbackReflectionInterface|null
   */
  private function reflectorGetCallback(\Reflector $reflector): ?CallbackReflectionInterface {

    if ($reflector instanceof \ReflectionClass) {

      if (!$reflector->isInstantiable()) {
        return null;
      }

      return new CallbackReflection_ClassConstruction($reflector);
    }

    if ($reflector instanceof \ReflectionMethod) {

      if ($reflector->isAbstract()) {
        return null;
      }

      if (!$reflector->isStatic()) {
        return null;
      }

      return new CallbackReflection_StaticMethod($reflector);
    }

    if ($reflector instanceof \ReflectionFunction) {
      return new CallbackReflection_Function($reflector);
    }

    return null;
  }

}
