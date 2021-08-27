<?php

declare(strict_types=1);

namespace Donquixote\ObCK\InlineDrilldown;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\ObCK\Formula\Formula;
use Donquixote\ObCK\Formula\Id\Formula_IdInterface;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\Formula\ValueToValue\Formula_ValueToValue;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\Plugin\Plugin;
use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Zoo\V2V\Drilldown\V2V_Drilldown_Trivial;
use Donquixote\ObCK\Zoo\V2V\Drilldown\V2V_DrilldownInterface;
use Donquixote\ObCK\Zoo\V2V\Value\V2V_Value_DrilldownFixedId;

abstract class InlineDrilldown_DecoKey implements InlineDrilldownInterface {

  /**
   * @STA
   *
   * @param \Donquixote\ObCK\Formula\DecoKey\Formula_DecoKeyInterface $formula
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $formulaToAnything
   *
   * @return \Donquixote\ObCK\InlineDrilldown\InlineDrilldownInterface
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromFormula(Formula_DecoKeyInterface $formula, FormulaToAnythingInterface $formulaToAnything) {
    return InlineDrilldown::fromFormula(
      $formula->getDecorated(),
      $formulaToAnything);
  }

}
