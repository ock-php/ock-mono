<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Drilldown;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\OCUI\Defmap\IdToFormula\IdToFormula_FromDefinitions;
use Donquixote\OCUI\Formula\Select\Formula_Select_FromDefinitions;
use Donquixote\OCUI\Util\UtilBase;

final class Formula_Drilldown_FromDefinitions extends UtilBase {

  /**
   * @param array $definitions
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\OCUI\Context\CfContextInterface|NULL $context
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function create(
    array $definitions,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    DefinitionToFormulaInterface $definitionToFormula,
    CfContextInterface $context = NULL
  ): Formula_DrilldownInterface {
    return new Formula_Drilldown(
      new Formula_Select_FromDefinitions(
        $definitions,
        $definitionToLabel,
        $definitionToGroupLabel),
      new IdToFormula_FromDefinitions(
        $definitions,
        $definitionToFormula,
        $context));
  }
}
