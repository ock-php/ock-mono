<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

abstract class SchemaReplacerPartial_IfaceBase implements SchemaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $schemas = [];

  /**
   * {@inheritdoc}
   */
  public function getSourceSchemaClass(): string {
    return Formula_IfaceWithContextInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(FormulaInterface $schema, SchemaReplacerInterface $replacer): ?FormulaInterface {

    if (!$schema instanceof Formula_IfaceWithContextInterface) {
      return NULL;
    }

    $k = $schema->getCacheId();

    return array_key_exists($k, $this->schemas)
      ? $this->schemas[$k]
      : $this->schemas[$k] = $this->schemaDoGetReplacement($schema, $replacer);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface $ifaceSchema
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  abstract protected function schemaDoGetReplacement(
    Formula_IfaceWithContextInterface $ifaceSchema,
    SchemaReplacerInterface $replacer
  ): ?FormulaInterface;
}
