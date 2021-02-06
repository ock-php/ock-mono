<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToSchema\Helper;

use Donquixote\CallbackReflection\Callback\CallbackReflectionInterface;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Exception\CfSchemaCreationException;

class DefinitionToSchemaHelper_Schema implements DefinitionToSchemaHelperInterface {

  /**
   * {@inheritdoc}
   */
  public function objectGetSchema($object): CfSchemaInterface {

    if ($object instanceof CfSchemaInterface) {
      return $object;
    }

    throw new CfSchemaCreationException("Object is not a CfSchema.");
  }

  /**
   * {@inheritdoc}
   */
  public function factoryGetSchema(CallbackReflectionInterface $factory, CfContextInterface $context = NULL): CfSchemaInterface {

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
        throw new CfSchemaCreationException("Leftover parameter '$paramName' for the factory callback provided.");
      }

      $serialArgs[] = $arg;
    }

    try {
      $candidate = $factory->invokeArgs($serialArgs);
    }
    catch (\Exception $e) {
      throw new CfSchemaCreationException("Exception in callback.", 0, $e);
    }

    if ($candidate instanceof CfSchemaInterface) {
      return $candidate;
    }

    if (!\is_object($candidate)) {
      $export = var_export($candidate, TRUE);
      throw new CfSchemaCreationException("The factory returned non-object value $export.");
    }

    $class = \get_class($candidate);
    throw new CfSchemaCreationException("The factory returned a non-CfSchema object of class $class.");
  }
}
