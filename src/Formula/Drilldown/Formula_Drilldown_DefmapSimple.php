<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Drilldown;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\ObCK\IdToFormula\IdToFormula_DefmapSimple;
use Donquixote\ObCK\Formula\Select\Formula_Select_FromDefinitionMap;
use Donquixote\ObCK\Util\UtilBase;

class Formula_Drilldown_DefmapSimple extends UtilBase {

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function create(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    CfContextInterface $context = NULL
  ): Formula_DrilldownInterface {
    return new Formula_Drilldown(
      new Formula_Select_FromDefinitionMap(
        $definitionMap,
        $definitionToLabel,
        $definitionToGroupLabel),
      new IdToFormula_DefmapSimple(
        $definitionMap,
        $context));
  }
}
