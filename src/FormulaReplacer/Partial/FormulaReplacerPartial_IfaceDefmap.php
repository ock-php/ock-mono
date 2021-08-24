<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\TypeToDefmap\TypeToDefmapInterface;
use Donquixote\ObCK\Formula\Defmap\Formula_Defmap;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\Formula\Neutral\Formula_Neutral_IfaceTransformed;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_IfaceDefmap implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\TypeToDefmap\TypeToDefmapInterface
   */
  private $typeToDefmap;

  /**
   * @var bool
   */
  private $withTaggingDecorator;

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface[]
   */
  private $formulas = [];

  /**
   * @param \Donquixote\ObCK\Defmap\TypeToDefmap\TypeToDefmapInterface $typeToDefmap
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
    // Accepts any formula.
    return Formula_IfaceInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $formula, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$formula instanceof Formula_IfaceInterface) {
      return NULL;
    }

    $k = $formula->getCacheId();

    // The value NULL does not occur, so isset() is safe.
    return $this->formulas[$k]
      ?? $this->formulas[$k] = $this->formulaDoGetReplacement($formula, $replacer);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface $ifaceFormula
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private function formulaDoGetReplacement(
    Formula_IfaceInterface $ifaceFormula,
    FormulaReplacerInterface $replacer
  ): FormulaInterface {

    $type = $ifaceFormula->getInterface();
    $context = $ifaceFormula->getContext();

    $defmap = $this->typeToDefmap->typeGetDefmap($type);

    $formula = new Formula_Defmap($defmap, $context);

    if (NULL !== $replacement = $replacer->formulaGetReplacement($formula)) {
      $formula = $replacement;
    }

    if ($this->withTaggingDecorator) {
      $formula = new Formula_Neutral_IfaceTransformed(
        $formula,
        $type,
        $context);
    }

    return $formula;
  }
}
