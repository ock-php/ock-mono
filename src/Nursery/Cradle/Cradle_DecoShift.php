<?php
declare(strict_types=1);

namespace Donquixote\Ock\Nursery\Cradle;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\DecoShift\Formula_DecoShiftInterface;
use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Formula\Group\Formula_Group;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\V2V\Group\V2V_Group_Deco;

/**
 * @STA
 */
class Cradle_DecoShift extends Cradle_FormulaReplacerBase {

  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct(Formula_DecoShiftInterface::class);
  }

  /**
   * {@inheritdoc}
   */
  protected function formulaGetReplacement(FormulaInterface $formula, NurseryInterface $nursery): ?FormulaInterface {
    /** @var \Donquixote\Ock\Formula\DecoShift\Formula_DecoShiftInterface $formula */
    return $this->decoratedFormulaGetReplacement($formula->getDecorated(), $nursery);
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $helper
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  private function decoratedFormulaGetReplacement(FormulaInterface $formula, NurseryInterface $helper): ?FormulaInterface {
    if ($formula instanceof Formula_GroupValInterface) {
      $group_v2v = $formula->getV2V();
      $group_formula = $formula->getDecorated();
    }
    elseif ($formula instanceof Formula_GroupInterface) {
      $group_v2v = NULL;
      $group_formula = $formula;
    }
    else {
      // Perhaps this is a group formula in disguise..
      $derived_formula = Formula::replace($formula, $helper);
      if ($derived_formula) {
        // @todo Detect infinite loops!
        return $this->decoratedFormulaGetReplacement($derived_formula, $helper);
      }
      // This is not a decorator formula.
      return NULL;
    }
    $item_formulas = $group_formula->getItemFormulas();
    $item_labels = $group_formula->getLabels();
    reset ($item_formulas);
    if (key($item_formulas) !== 'decorated') {
      return NULL;
    }
    unset($item_formulas['decorated']);
    unset($item_labels['decorated']);
    return new Formula_GroupVal(
      new Formula_Group($item_formulas, $item_labels),
      V2V_Group_Deco::create($group_v2v));
  }

}
