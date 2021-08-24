<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionToFormula\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Exception\FormulaCreationException;

class DefinitionToFormulaHelper_Formula implements DefinitionToFormulaHelperInterface {

  /**
   * {@inheritdoc}
   */
  public function objectGetFormula($object): FormulaInterface {

    if ($object instanceof FormulaInterface) {
      return $object;
    }

    throw new FormulaCreationException("Object is not a Formula.");
  }

  /**
   * {@inheritdoc}
   */
  public function factoryGetFormula(CallbackReflectionInterface $factory, CfContextInterface $context = NULL): FormulaInterface {

    $serialArgs = [];
    foreach ($factory->getReflectionParameters() as $param) {

      // @todo Only accept optional parameters.
      if ($context && $context->paramValueExists($param)) {
        $arg = $context->paramGetValue($param);
      }
      elseif ($param->isOptional()) {
        try {
          $arg = $param->getDefaultValue();
        }
        catch (\ReflectionException $e) {
          throw new \RuntimeException('Impossible exception', 0, $e);
        }
      }
      else {
        $paramName = $param->getName();
        throw new FormulaCreationException("Leftover parameter '$paramName' for the factory callback provided.");
      }

      $serialArgs[] = $arg;
    }

    try {
      $candidate = $factory->invokeArgs($serialArgs);
    }
    catch (\Exception $e) {
      throw new FormulaCreationException("Exception in callback.", 0, $e);
    }

    if ($candidate instanceof FormulaInterface) {
      return $candidate;
    }

    if (!\is_object($candidate)) {
      $export = var_export($candidate, TRUE);
      throw new FormulaCreationException("The factory returned non-object value $export.");
    }

    $class = \get_class($candidate);
    throw new FormulaCreationException("The factory returned a non-Formula object of class $class.");
  }
}
