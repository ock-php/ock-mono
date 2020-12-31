<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\IdToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Exception\CfSchemaCreationException;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;

class IdToSchema_FromDefinitions implements IdToSchemaInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array[] $definitions
   * @param \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    array $definitions,
    DefinitionToSchemaInterface $definitionToSchema,
    CfContextInterface $context = NULL
  ) {
    $this->definitions = $definitions;
    $this->definitionToSchema = $definitionToSchema;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?CfSchemaInterface {

    if (!isset($this->definitions[$id])) {
      return NULL;
    }

    try {
      return $this->definitionToSchema->definitionGetSchema(
        $this->definitions[$id],
        $this->context);
    }
    catch (CfSchemaCreationException $e) {
      // @todo Maybe report this somewhere?
      return NULL;
    }
  }
}
