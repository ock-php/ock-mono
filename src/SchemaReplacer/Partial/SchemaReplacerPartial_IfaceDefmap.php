<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface;
use Donquixote\OCUI\Schema\Defmap\CfSchema_Defmap;
use Donquixote\OCUI\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\OCUI\Schema\Neutral\CfSchema_Neutral_IfaceTransformed;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

class SchemaReplacerPartial_IfaceDefmap implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var bool
   */
  private $withTaggingDecorator;

  /**
   * @var \Donquixote\OCUI\Core\Schema\CfSchemaInterface[]
   */
  private $schemas = [];

  /**
   * @param \Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
   * @param bool $withTaggingDecorator
   */
  public function __construct(
    TypeToDefmapInterface $typeToDefmap,
    $withTaggingDecorator = TRUE
  ) {
    $this->typeToDefmap = $typeToDefmap;
    $this->withTaggingDecorator = $withTaggingDecorator;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    // Accepts any schema.
    return CfSchema_IfaceWithContextInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer): ?CfSchemaInterface {

    if (!$schema instanceof CfSchema_IfaceWithContextInterface) {
      return NULL;
    }

    $k = $schema->getCacheId();

    // The value NULL does not occur, so isset() is safe.
    return $this->schemas[$k]
      ?? $this->schemas[$k] = $this->schemaDoGetReplacement($schema, $replacer);
  }

  /**
   * @param \Donquixote\OCUI\Schema\Iface\CfSchema_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  private function schemaDoGetReplacement(
    CfSchema_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ): CfSchemaInterface {

    $type = $ifaceSchema->getInterface();
    $context = $ifaceSchema->getContext();

    $defmap = $this->typeToDefmap->typeGetDefmap($type);

    $schema = new CfSchema_Defmap($defmap, $context);

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    if ($this->withTaggingDecorator) {
      $schema = new CfSchema_Neutral_IfaceTransformed(
        $schema,
        $type,
        $context);
    }

    return $schema;
  }
}
