<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\IdToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Exception\CfSchemaCreationException;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;

class IdToSchema_FromDefinitionMap implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToSchemaInterface $definitionToSchema,
    CfContextInterface $context = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->definitionToSchema = $definitionToSchema;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?CfSchemaInterface {

    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }

    try {
      return $this->definitionToSchema->definitionGetSchema($definition, $this->context);
    }
    catch (CfSchemaCreationException $e) {
      // @todo Maybe report this somewhere?
      return NULL;
    }
  }
}
