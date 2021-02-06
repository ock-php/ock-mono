<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\OCUI\Formula\Definition\Formula_DefinitionInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_Definition implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   */
  public function __construct(DefinitionToSchemaInterface $definitionToSchema) {
    $this->definitionToSchema = $definitionToSchema;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    return Formula_DefinitionInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(FormulaInterface $schema, SchemaReplacerInterface $replacer): ?FormulaInterface {

    if (!$schema instanceof Formula_DefinitionInterface) {
      return NULL;
    }

    try {
      $schema = $this->definitionToSchema->definitionGetSchema(
        $schema->getDefinition(),
        $schema->getContext());
    }
    catch (\Exception $e) {
      // @todo Allow throwing exceptions? Log the problem somewhere? BrokenSchema?
      return NULL;
    }

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    return $schema;
  }
}
