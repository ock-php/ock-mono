<?php

declare(strict_types=1);

namespace Donquixote\Ock\InlineDrilldown;

use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\Ock\Nursery\NurseryInterface;
use Donquixote\Ock\V2V\Group\V2V_Group_Trivial;
use Donquixote\Ock\V2V\Group\V2V_GroupInterface;
use Donquixote\Ock\V2V\Value\V2V_Value_GroupV2V;

abstract class InlineDrilldown_Group implements InlineDrilldownInterface {

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function fromGroup(Formula_GroupInterface $formula, NurseryInterface $formulaToAnything): ?InlineDrilldownInterface {
    return self::create($formula, new V2V_Group_Trivial(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\Ock\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function fromGroupVal(Formula_GroupValInterface $formula, NurseryInterface $formulaToAnything): ?InlineDrilldownInterface {
    return self::create($formula->getDecorated(), $formula->getV2V(), $formulaToAnything);
  }

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\Ock\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\Ock\Nursery\NurseryInterface $formulaToAnything
   *
   * @return \Donquixote\Ock\InlineDrilldown\InlineDrilldownInterface|null
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $groupFormula, V2V_GroupInterface $v2v, NurseryInterface $formulaToAnything): ?InlineDrilldownInterface {
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
