<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\IdToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\OCUI\Exception\CfSchemaCreationException;
use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;

class IdToSchema_FromDefinitions implements IdToSchemaInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array[] $definitions
   * @param \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
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
