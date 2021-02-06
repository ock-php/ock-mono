<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\Definitions\CfSchema_Definitions;
use Donquixote\OCUI\Schema\Iface\CfSchema_IfaceWithContextInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

abstract class SchemaReplacerPartial_IfaceDefinitionsBase extends SchemaReplacerPartial_IfaceBase {

  /**
   * @param \Donquixote\OCUI\Schema\Iface\CfSchema_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  protected function schemaDoGetReplacement(
    CfSchema_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ): ?CfSchemaInterface {

    $type = $ifaceSchema->getInterface();
    $context = $ifaceSchema->getContext();

    $definitions = $this->typeGetDefinitions($type);

    $schema = new CfSchema_Definitions($definitions, $context);

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    return $schema;
  }

  /**
   * @param string $type
   *
   * @return array[]
   */
  abstract protected function typeGetDefinitions(string $type): array;
}
