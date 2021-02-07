<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\ToDrilldownFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_Drilldown_FromDefinitionMap;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;

class DefmapToDrilldownFormula implements DefmapToDrilldownFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   */
  public function __construct(
    DefinitionToFormulaInterface $definitionToFormula,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel
  ) {
    $this->definitionToFormula = $definitionToFormula;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
  }

  /**
   * {@inheritdoc}
   */
  public function defmapGetDrilldownFormula(DefinitionMapInterface $definitionMap, CfContextInterface $context = NULL): Formula_DrilldownInterface {

    return Formula_Drilldown_FromDefinitionMap::create(
      $definitionMap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToFormula,
      $context);
  }
}
