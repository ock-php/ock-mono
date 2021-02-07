<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Definitions\Formula_Definitions;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

abstract class SchemaReplacerPartial_IfaceDefinitionsBase extends SchemaReplacerPartial_IfaceBase {

  /**
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  protected function schemaDoGetReplacement(
    Formula_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ): ?FormulaInterface {

    $type = $ifaceSchema->getInterface();
    $context = $ifaceSchema->getContext();

    $definitions = $this->typeGetDefinitions($type);

    $schema = new Formula_Definitions($definitions, $context);

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
