<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\OCUI\Formula\Definitions\Formula_DefinitionsInterface;
use Donquixote\OCUI\Formula\Drilldown\CfSchema_Drilldown_FromDefinitions;
use Donquixote\OCUI\Formula\Drilldown\CfSchema_Drilldown_InlineExpanded;
use Donquixote\OCUI\Formula\Id\CfSchema_Id_DefinitionsKey;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_DefinitionsDrilldown implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @var bool
   */
  private $withInlineChildren;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   * @param bool $withInlineChildren
   */
  public function __construct(
    DefinitionToSchemaInterface $definitionToSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel,
    $withInlineChildren = TRUE
  ) {
    $this->definitionToSchema = $definitionToSchema;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
    $this->withInlineChildren = $withInlineChildren;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    return Formula_DefinitionsInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(FormulaInterface $original, SchemaReplacerInterface $replacer): ?FormulaInterface {

    if (!$original instanceof Formula_DefinitionsInterface) {
      return NULL;
    }

    $definitions = $original->getDefinitions();
    $context = $original->getContext();

    $schema = CfSchema_Drilldown_FromDefinitions::create(
      $definitions,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToSchema,
      $context);

    if ($this->withInlineChildren) {

      $inlineIdsLookup = new CfSchema_Id_DefinitionsKey(
        $definitions,
        'inline');

      $schema = CfSchema_Drilldown_InlineExpanded::create(
        $schema,
        $inlineIdsLookup);
    }

    return $schema;
  }
}
