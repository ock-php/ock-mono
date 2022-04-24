<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Adaptism\Attribute\Adapter;
use Donquixote\Adaptism\Attribute\Parameter\Adaptee;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\PluginList\Formula_PluginListInterface;
use Donquixote\Ock\Formula\Select\Formula_Select_FromPlugins;
use Donquixote\Ock\IdToFormula\IdToFormula_FromPlugins;

class SpecificAdapter_PluginList {

  /**
   * @throws \Donquixote\Ock\Exception\FormulaException
   */
  #[Adapter]
  public static function formulaGetReplacement(
    #[Adaptee] Formula_PluginListInterface $formula,
  ): ?FormulaInterface {
    $plugins = $formula->getPlugins();
    $ff = (new Formula_Drilldown(
      new Formula_Select_FromPlugins($plugins),
      new IdToFormula_FromPlugins($plugins),
      $formula->allowsNull()));

    return $ff;
  }

}
