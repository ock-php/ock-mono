<?php

declare(strict_types=1);

namespace Donquixote\Ock\Adapter;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Adaptism\Attribute\Parameter\UniversalAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\DecoShift\Formula_DecoShiftInterface;
use Donquixote\Ock\Formula\Group\Formula_Group;
use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupVal;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\V2V\Group\V2V_Group_Deco;

class SpecificAdapter_DecoShift {

  /**
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  #[Adapter]
  public static function adapt(
    #[Adaptee] Formula_DecoShiftInterface $formula,
    #[UniversalAdapter] UniversalAdapterInterface $universalAdapter,
  ): ?FormulaInterface {
    return static::adaptDecorated(
       $formula->getDecorated(),
      $universalAdapter,
    );
  }

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   */
  protected static function adaptDecorated(
    FormulaInterface $formula,
    UniversalAdapterInterface $universalAdapter,
  ): ?FormulaInterface {
    if ($formula instanceof Formula_GroupValInterface) {
      $group_v2v = $formula->getV2V();
      $group_formula = $formula->getDecorated();
    }
    elseif ($formula instanceof Formula_GroupInterface) {
      $group_v2v = null;
      $group_formula = $formula;
    }
    else {
      $derived_formula = $universalAdapter->adapt(
        $formula,
        Formula_GroupValInterface::class);
      if ($derived_formula !== null) {
        $group_v2v = $derived_formula->getV2V();
        $group_formula = $derived_formula->getDecorated();
      }
      else {
        $group_formula = $universalAdapter->adapt(
          $formula,
          Formula_GroupInterface::class);
        if ($group_formula === null) {
          return null;
        }
        $group_v2v = null;
      }
    }
    $item_formulas = $group_formula->getItemFormulas();
    $item_labels = $group_formula->getLabels();
    reset($item_formulas);
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
