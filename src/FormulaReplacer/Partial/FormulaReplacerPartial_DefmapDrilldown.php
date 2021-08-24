<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\ObCK\Formula\Defmap\Formula_DefmapInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown_FromDefinitionMap;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown_InlineExpanded;
use Donquixote\ObCK\Formula\Id\Formula_Id_DefmapKey;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_DefmapDrilldown implements FormulaReplacerPartialInterface {

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
   * @var bool
   */
  private $withInlineChildren;

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\ObCK\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
   * @param bool $withInlineChildren
   */
  public function __construct(
    DefinitionToFormulaInterface $definitionToFormula,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGrouplabel,
    $withInlineChildren = TRUE
  ) {
    $this->definitionToFormula = $definitionToFormula;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGrouplabel = $definitionToGrouplabel;
    $this->withInlineChildren = $withInlineChildren;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFormulaClass(): string {
    return Formula_DefmapInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $original, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$original instanceof Formula_DefmapInterface) {
      return NULL;
    }

    $defmap = $original->getDefinitionMap();
    $context = $original->getContext();

    $formula = Formula_Drilldown_FromDefinitionMap::create(
      $defmap,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToFormula,
      $context);

    if ($this->withInlineChildren) {

      $inlineIdsLookup = new Formula_Id_DefmapKey(
        $defmap,
        'inline');

      $formula = Formula_Drilldown_InlineExpanded::create(
        $formula,
        $inlineIdsLookup);
    }

    return $formula;
  }
}
