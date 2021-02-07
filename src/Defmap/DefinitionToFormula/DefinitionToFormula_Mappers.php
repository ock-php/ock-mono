<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToFormula;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelper_Handler;
use Donquixote\OCUI\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelper_Formula;
use Donquixote\OCUI\Exception\FormulaCreationException;
use Psr\Log\LoggerInterface;

class DefinitionToFormula_Mappers implements DefinitionToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelperInterface[]
   */
  private $helpers;

  /**
   * @var \Psr\Log\LoggerInterface
   */
  private $logger;

  /**
   * @param \Psr\Log\LoggerInterface $logger
   *
   * @return self
   */
  public static function create(LoggerInterface $logger): DefinitionToFormula_Mappers {
    return new self(
      [
        'schema' => new DefinitionToFormulaHelper_Formula(),
        'handler' => new DefinitionToFormulaHelper_Handler(),
      ],
      $logger);
  }

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToFormula\Helper\DefinitionToFormulaHelperInterface[] $helpers
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function __construct(array $helpers, LoggerInterface $logger) {
    $this->helpers = $helpers;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function definitionGetFormula(array $definition, CfContextInterface $context = NULL): FormulaInterface {

    foreach ($this->helpers as $prefix => $helper) {

      if (isset($definition[$key = $prefix . '_class'])) {
        $factory = CallbackReflection_ClassConstruction::createFromClassNameCandidate(
          $definition[$key]);

        if (NULL === $factory) {
          $this->logger->warning(
            'Formula definition specifies {key} => {value}, but it seems the class does not exist. Clearing the cache might help.',
            [
              'key' => $key,
              'value' => $definition[$key],
            ]);

          continue;
        }
      }
      elseif (isset($definition[$key = $prefix . '_factory'])) {
        $factory = CallbackUtil::callableGetCallback($definition[$key]);

        if (NULL === $factory) {
          $this->logger->warning(
            'Formula definition specifies {key} => {value}, but it seems the callback does not exist. Clearing the cache might help.',
            [
              'key' => $key,
              'value' => $definition[$key],
            ]);

          continue;
        }
      }
      else {
        if (isset($definition[$prefix])) {
          $candidate = $definition[$prefix];
          if (!\is_object($candidate)) {
            $export = var_export($candidate, TRUE);
            throw new FormulaCreationException("Candidate is non-object $export.");
          }
          return $helper->objectGetFormula($candidate);
        }
        continue;
      }

      if (!empty($definition[$argsKey = $prefix . '_arguments'])) {
        // Currying!
        $factory = new CallbackReflection_BoundParameters(
          $factory, $definition[$argsKey]);
      }

      return $helper->factoryGetFormula($factory, $context);
    }

    throw new FormulaCreationException("None of the mappers was applicable to the definition provided.");
  }
}
