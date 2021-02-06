<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\ToDrilldownSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\OCUI\Formula\Drilldown\CfSchema_DrilldownInterface;

interface DefmapToDrilldownSchemaInterface {

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Formula\Drilldown\CfSchema_DrilldownInterface
   */
  public function defmapGetDrilldownSchema(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL): CfSchema_DrilldownInterface;

}
