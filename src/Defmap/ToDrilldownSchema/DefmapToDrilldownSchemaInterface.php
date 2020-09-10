<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\ToDrilldownSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;

interface DefmapToDrilldownSchemaInterface {

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Context\CfContextInterface $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public function defmapGetDrilldownSchema(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL): CfSchema_DrilldownInterface;

}
