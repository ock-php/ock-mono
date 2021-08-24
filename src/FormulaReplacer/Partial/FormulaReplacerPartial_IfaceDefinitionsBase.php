<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Definitions\Formula_Definitions;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

abstract class FormulaReplacerPartial_IfaceDefinitionsBase extends FormulaReplacerPartial_IfaceBase {

  /**
   * @param \Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface $ifaceFormula
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  protected function formulaDoGetReplacement(
    Formula_IfaceInterface $ifaceFormula,
    FormulaReplacerInterface $replacer
  ): ?FormulaInterface {

    $type = $ifaceFormula->getInterface();
    $context = $ifaceFormula->getContext();

    $definitions = $this->typeGetDefinitions($type);

    $formula = new Formula_Definitions($definitions, $context);

    if (NULL !== $replacement = $replacer->formulaGetReplacement($formula)) {
      $formula = $replacement;
    }

    return $formula;
  }

  /**
   * @param string $type
   *
   * @return array[]
   */
  abstract protected function typeGetDefinitions(string $type): array;
}
