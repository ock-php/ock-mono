<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Schema\Defmap\CfSchema_DefmapInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_FromDefinitionMap;
use Donquixote\Cf\Schema\Drilldown\CfSchema_Drilldown_InlineExpanded;
use Donquixote\Cf\Schema\Id\CfSchema_Id_DefmapKey;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_DefmapDrilldown implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @var bool
   */
  private $withInlineChildren;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
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
    return CfSchema_DefmapInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(CfSchemaInterface $original, SchemaReplacerInterface $replacer): ?CfSchemaInterface {

    if (!$original instanceof CfSchema_DefmapInterface) {
      return NULL;
    }

    $defmap = $original->getDefinitionMap();
    $context = $original->getContext();

    $schema = CfSchema_Drilldown_FromDefinitionMap::create(
      $defmap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToSchema,
      $context);

    if ($this->withInlineChildren) {

      $inlineIdsLookup = new CfSchema_Id_DefmapKey(
        $defmap,
        'inline');

      $schema = CfSchema_Drilldown_InlineExpanded::create(
        $schema,
        $inlineIdsLookup);
    }

    return $schema;
  }
}
