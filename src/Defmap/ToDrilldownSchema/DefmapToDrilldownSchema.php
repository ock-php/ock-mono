<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\ToDrilldownSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface;
use Donquixote\OCUI\Formula\Drilldown\CfSchema_Drilldown_FromDefinitionMap;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;

class DefmapToDrilldownSchema implements DefmapToDrilldownSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface
   */
  private $definitionToSchema;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToSchema\DefinitionToSchemaInterface $definitionToSchema
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   */
  public function __construct(
    DefinitionToSchemaInterface $definitionToSchema,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel
  ) {
    $this->definitionToSchema = $definitionToSchema;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
  }

  /**
   * {@inheritdoc}
   */
  public function defmapGetDrilldownSchema(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL): Formula_DrilldownInterface {

    return CfSchema_Drilldown_FromDefinitionMap::create(
      $definitionMap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToSchema,
      $context);
  }
}
