<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\IdToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface;
use Donquixote\OCUI\Exception\CfSchemaCreationException;
use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;

class IdToSchema_ViaDefinition implements IdToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface $idToDefinition
   * @param \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   */
  public function __construct(
    IdToDefinitionInterface $idToDefinition,
    DefinitionToSchemaInterface $definitionToSchema,
    CfContextInterface $context = NULL
  ) {
    $this->idToDefinition = $idToDefinition;
    $this->definitionToSchema = $definitionToSchema;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?FormulaInterface {

    $definition = $this->idToDefinition->idGetDefinition($id);

    if (NULL === $definition) {
      return NULL;
    }

    try {
      return $this->definitionToSchema->definitionGetSchema($definition, $this->context);
    }
    catch (CfSchemaCreationException $e) {
      // @todo Report this in watchdog?
      return NULL;
    }
  }
}
