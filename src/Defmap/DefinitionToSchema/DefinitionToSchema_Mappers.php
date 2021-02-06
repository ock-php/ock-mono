<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToSchema;

use Donquixote\CallbackReflection\Callback\CallbackReflection_BoundParameters;
use Donquixote\CallbackReflection\Callback\CallbackReflection_ClassConstruction;
use Donquixote\CallbackReflection\Util\CallbackUtil;
use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelper_Handler;
use Donquixote\OCUI\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelper_Schema;
use Donquixote\OCUI\Exception\CfSchemaCreationException;
use Psr\Log\LoggerInterface;

class DefinitionToSchema_Mappers implements DefinitionToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelperInterface[]
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
  public static function create(LoggerInterface $logger): DefinitionToSchema_Mappers {
    return new self(
      [
        'schema' => new DefinitionToSchemaHelper_Schema(),
        'handler' => new DefinitionToSchemaHelper_Handler(),
      ],
      $logger);
  }

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToSchema\Helper\DefinitionToSchemaHelperInterface[] $helpers
   * @param \Psr\Log\LoggerInterface $logger
   */
  public function __construct(array $helpers, LoggerInterface $logger) {
    $this->helpers = $helpers;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL): CfSchemaInterface {

    foreach ($this->helpers as $prefix => $helper) {

      if (isset($definition[$key = $prefix . '_class'])) {
        $factory = CallbackReflection_ClassConstruction::createFromClassNameCandidate(
          $definition[$key]);

        if (NULL === $factory) {
          $this->logger->warning(
            'Schema definition specifies {key} => {value}, but it seems the class does not exist. Clearing the cache might help.',
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
            'Schema definition specifies {key} => {value}, but it seems the callback does not exist. Clearing the cache might help.',
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
            throw new CfSchemaCreationException("Candidate is non-object $export.");
          }
          return $helper->objectGetSchema($candidate);
        }
        continue;
      }

      if (!empty($definition[$argsKey = $prefix . '_arguments'])) {
        // Currying!
        $factory = new CallbackReflection_BoundParameters(
          $factory, $definition[$argsKey]);
      }

      return $helper->factoryGetSchema($factory, $context);
    }

    throw new CfSchemaCreationException("None of the mappers was applicable to the definition provided.");
  }
}
