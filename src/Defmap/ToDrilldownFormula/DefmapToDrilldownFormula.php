<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\ToDrilldownFormula;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown_FromDefinitionMap;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;

class DefmapToDrilldownFormula implements DefmapToDrilldownFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGrouplabel;

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
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
