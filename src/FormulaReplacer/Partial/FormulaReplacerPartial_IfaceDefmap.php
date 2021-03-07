<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToDefmap\TypeToDefmapInterface;
use Donquixote\OCUI\Formula\Defmap\Formula_Defmap;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface;
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
  private $formulas = [];

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
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface $ifaceFormula
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
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
