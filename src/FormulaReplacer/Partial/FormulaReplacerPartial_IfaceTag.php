<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface;
use Donquixote\OCUI\Formula\Neutral\Formula_Neutral_IfaceTransformed;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_IfaceTag extends FormulaReplacerPartial_IfaceBase {

  /**
   * @var \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\OCUI\FormulaReplacer\Partial\FormulaReplacerPartialInterface $decorated
   */
  public function __construct(FormulaReplacerPartialInterface $decorated) {
    $this->decorated = $decorated;
  }

  /**
   * @param \Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface $ifaceFormula
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  protected function formulaDoGetReplacement(
    Formula_IfaceInterface $ifaceFormula,
    FormulaReplacerInterface $replacer
  ): ?FormulaInterface {

    if (NULL === $formula = $this->decorated->formulaGetReplacement($ifaceFormula, $replacer)) {
      // @todo Tag this one as well?
      return NULL;
      # $formula = $ifaceFormula;
    }

    $formula = new Formula_Neutral_IfaceTransformed(
      $formula,
      $ifaceFormula->getInterface(),
      $ifaceFormula->getContext());

    return $formula;
  }
}
