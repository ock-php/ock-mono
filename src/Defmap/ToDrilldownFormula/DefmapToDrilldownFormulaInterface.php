<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\ToDrilldownFormula;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;

interface DefmapToDrilldownFormulaInterface {

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  public function defmapGetDrilldownFormula(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL): Formula_DrilldownInterface;

}
