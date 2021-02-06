<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Drilldown;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\OCUI\Defmap\IdToSchema\IdToSchema_FromDefinitions;
use Donquixote\OCUI\Schema\Select\CfSchema_Select_FromDefinitions;
use Donquixote\OCUI\Util\UtilBase;

final class CfSchema_Drilldown_FromDefinitions extends UtilBase {

  /**
   * @param array $definitions
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\OCUI\Context\CfContextInterface|NULL $context
   *
   * @return \Donquixote\OCUI\Schema\Drilldown\CfSchema_DrilldownInterface
   */
  public static function create(
    array $definitions,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel,
    DefinitionToSchemaInterface $definitionToSchema,
    CfContextInterface $context = NULL
  ): CfSchema_DrilldownInterface {
    return new CfSchema_Drilldown(
      new CfSchema_Select_FromDefinitions(
        $definitions,
        $definitionToLabel,
        $definitionToGroupLabel),
      new IdToSchema_FromDefinitions(
        $definitions,
        $definitionToSchema,
        $context));
  }
}
