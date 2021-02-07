<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Definitions\Formula_Definitions;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

abstract class FormulaReplacerPartial_IfaceDefinitionsBase extends FormulaReplacerPartial_IfaceBase {

  /**
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceWithContextInterface $ifaceFormula
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  protected function formulaDoGetReplacement(
    Formula_IfaceWithContextInterface $ifaceFormula,
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
