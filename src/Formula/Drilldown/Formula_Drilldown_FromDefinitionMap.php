<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Drilldown;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormula_FromDefinitionMap;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromDefinitionMap;
use Donquixote\ObCK\Util\UtilBase;

final class Formula_Drilldown_FromDefinitionMap extends UtilBase {

  /**
   * @param DefinitionMapInterface $definitionMap
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\ObCK\Context\CfContextInterface|NULL $context
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function create(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    DefinitionToFormulaInterface $definitionToFormula,
    CfContextInterface $context = NULL
  ): Formula_DrilldownInterface {
    return new Formula_Drilldown(
      new Formula_Select_FromDefinitionMap(
        $definitionMap,
        $definitionToLabel,
        $definitionToGroupLabel),
      new IdToFormula_FromDefinitionMap(
        $definitionMap,
        $definitionToFormula,
        $context));
  }
}
