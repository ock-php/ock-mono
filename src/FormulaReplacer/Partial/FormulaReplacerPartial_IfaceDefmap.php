<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface;
use Donquixote\OCUI\Formula\Defmap\Formula_Defmap;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface;
use Donquixote\OCUI\Formula\Neutral\Formula_Neutral_IfaceTransformed;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_IfaceDefmap implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var bool
   */
  private $withTaggingDecorator;

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface[]
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
  public function getSourceFormulaClass(): string {
    // Accepts any schema.
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

    // The value NULL does not occur, so isset() is safe.
    return $this->schemas[$k]
      ?? $this->schemas[$k] = $this->schemaDoGetReplacement($schema, $replacer);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface $ifaceFormula
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private function schemaDoGetReplacement(
    Formula_IfaceWithContextInterface $ifaceFormula,
    FormulaReplacerInterface $replacer
  ): FormulaInterface {

    $type = $ifaceFormula->getInterface();
    $context = $ifaceFormula->getContext();

    $defmap = $this->typeToDefmap->typeGetDefmap($type);

    $schema = new Formula_Defmap($defmap, $context);

    if (NULL !== $replacement = $replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    if ($this->withTaggingDecorator) {
      $schema = new Formula_Neutral_IfaceTransformed(
        $schema,
        $type,
        $context);
    }

    return $schema;
  }
}
