<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Schema\Definition\CfSchema_DefinitionInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_Definition implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   */
  public function __construct(DefinitionToSchemaInterface $definitionToSchema) {
    $this->definitionToSchema = $definitionToSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    return CfSchema_DefinitionInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer): ?CfSchemaInterface {

    if (!$schema instanceof CfSchema_DefinitionInterface) {
      return NULL;
    }

    try {
      $schema = $this->definitionToSchema->definitionGetSchema(
        $schema->getDefinition(),
        $schema->getContext());
    }
    catch (\Exception $e) {
      dpm($schema->getDefinition(), $e->getMessage());
      // @todo Allow throwing exceptions? Log the problem somewhere? BrokenSchema?
      return NULL;
    }

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      /** @noinspection CallableParameterUseCaseInTypeContextInspection */
      $schema = $replacement;
    }

    return $schema;
  }
}
