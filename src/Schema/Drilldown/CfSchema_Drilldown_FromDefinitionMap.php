<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Drilldown;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\Cf\Defmap\IdToSchema\IdToSchema_FromDefinitionMap;
use Donquixote\Cf\Schema\Select\CfSchema_Select_FromDefinitionMap;
use Donquixote\Cf\Util\UtilBase;

final class CfSchema_Drilldown_FromDefinitionMap extends UtilBase {

  /**
   * @param DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Donquixote\Cf\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\Cf\Context\CfContextInterface|NULL $context
   *
   * @return \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function create(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    DefinitionToSchemaInterface $definitionToSchema,
    CfContextInterface $context = NULL
  ): CfSchema_DrilldownInterface {
    return new CfSchema_Drilldown(
      new CfSchema_Select_FromDefinitionMap(
        $definitionMap,
        $definitionToLabel,
        $definitionToGroupLabel),
      new IdToSchema_FromDefinitionMap(
        $definitionMap,
        $definitionToSchema,
        $context));
  }
}
