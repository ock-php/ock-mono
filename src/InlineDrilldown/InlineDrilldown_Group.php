<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlineDrilldown;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\V2V\Group\V2V_Group_Trivial;
use Donquixote\ObCK\V2V\Group\V2V_GroupInterface;
use Donquixote\ObCK\V2V\Value\V2V_Value_GroupV2V;

abstract class InlineDrilldown_Group implements InlineDrilldownInterface {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromGroup(Formula_GroupInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?InlineDrilldownInterface {
    return self::create($formula, new V2V_Group_Trivial(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromGroupVal(Formula_GroupValInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?InlineDrilldownInterface {
    return self::create($formula->getDecorated(), $formula->getV2V(), $formulaToAnything);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\ObCK\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $groupFormula, V2V_GroupInterface $v2v, FormulaToAnythingInterface $formulaToAnything): ?InlineDrilldownInterface {
    $itemFormulas = $groupFormula->getItemFormulas();
    if (count($itemFormulas) !== 1) {
      return NULL;
    }
    $itemDrilldown = InlineDrilldown::fromFormula(
      reset($itemFormulas),
      $formulaToAnything);
    if ($itemDrilldown === NULL) {
      return NULL;
    }
    return new InlineDrilldown_V2V(
      $itemDrilldown,
      new V2V_Value_GroupV2V($v2v, key($itemFormulas)));
  }

}
