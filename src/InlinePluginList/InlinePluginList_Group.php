<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlinePluginList;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Zoo\V2V\Group\V2V_Group_Trivial;
use Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface;
use Donquixote\ObCK\Zoo\V2V\Value\V2V_Value_GroupV2V;

abstract class InlinePluginList_Group implements InlinePluginListInterface {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\InlinePluginList\InlinePluginListInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromGroup(Formula_GroupInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?InlinePluginListInterface {
    return self::create($formula, new V2V_Group_Trivial(), $formulaToAnything);
  }

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\InlinePluginList\InlinePluginListInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromGroupVal(Formula_GroupValInterface $formula, FormulaToAnythingInterface $formulaToAnything): ?InlinePluginListInterface {
    return self::create($formula->getDecorated(), $formula->getV2V(), $formulaToAnything);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $groupFormula
   * @param \Donquixote\ObCK\Zoo\V2V\Group\V2V_GroupInterface $v2v
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\InlinePluginList\InlinePluginListInterface|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function create(Formula_GroupInterface $groupFormula, V2V_GroupInterface $v2v, FormulaToAnythingInterface $formulaToAnything): ?InlinePluginListInterface {
    $itemFormulas = $groupFormula->getItemFormulas();
    if (count($itemFormulas) !== 1) {
      return NULL;
    }
    $itemFormula = reset($itemFormulas);
    $key = key($itemFormulas);
    $itemPluginList = InlinePluginList::fromFormula($itemFormula, $formulaToAnything);
    if ($itemPluginList === NULL) {
      return NULL;
    }
    return new InlinePluginList_V2V(
      $itemPluginList,
      new V2V_Value_GroupV2V($v2v, $key));
  }

}
