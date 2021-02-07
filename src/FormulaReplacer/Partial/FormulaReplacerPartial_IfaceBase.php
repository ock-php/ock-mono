<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

abstract class FormulaReplacerPartial_IfaceBase implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
   */
  private $schemas = [];

  /**
   * {@inheritdoc}
   */
  public function getSourceFormulaClass(): string {
    return Formula_IfaceWithContextInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function schemaGetReplacement(FormulaInterface $schema, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$schema instanceof Formula_IfaceWithContextInterface) {
      return NULL;
    }

    $k = $schema->getCacheId();

    return array_key_exists($k, $this->schemas)
      ? $this->schemas[$k]
      : $this->schemas[$k] = $this->schemaDoGetReplacement($schema, $replacer);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface $ifaceFormula
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  abstract protected function schemaDoGetReplacement(
    Formula_IfaceWithContextInterface $ifaceFormula,
    FormulaReplacerInterface $replacer
  ): ?FormulaInterface;
}
