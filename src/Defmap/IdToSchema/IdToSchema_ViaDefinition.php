<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\IdToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Defmap\IdToDefinition\IdToDefinitionInterface;
use Donquixote\Cf\Exception\CfSchemaCreationException;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;

class IdToSchema_ViaDefinition implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\Defmap\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\Defmap\IdToDefinition\IdToDefinitionInterface $idToDefinition
   * @param \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
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
  public function idGetSchema($id): ?CfSchemaInterface {

    $definition = $this->idToDefinition->idGetDefinition($id);

    if (NULL === $definition) {
      return NULL;
    }

    try {
      return $this->definitionToSchema->definitionGetSchema($definition, $this->context);
    }
    catch (CfSchemaCreationException $e) {
      dpm($definition, $e->getMessage());
      // @todo Report this in watchdog?
      return NULL;
    }
  }
}
