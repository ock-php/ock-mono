<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceInterface;
use Donquixote\ObCK\Formula\Neutral\Formula_Neutral_IfaceTransformed;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_IfaceTag extends FormulaReplacerPartial_IfaceBase {

  /**
   * @var \Donquixote\ObCK\FormulaReplacer\Partial\FormulaReplacerPartialInterface
   */
  private $decorated;

  /**
   * @param \Donquixote\ObCK\FormulaReplacer\Partial\FormulaReplacerPartialInterface $decorated
   */
  public function __construct(FormulaReplacerPartialInterface $decorated) {
    $this->decorated = $decorated;
  }

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
