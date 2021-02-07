<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\OCUI\Formula\Definitions\Formula_DefinitionsInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_Drilldown_FromDefinitions;
use Donquixote\OCUI\Formula\Drilldown\Formula_Drilldown_InlineExpanded;
use Donquixote\OCUI\Formula\Id\Formula_Id_DefinitionsKey;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_DefinitionsDrilldown implements FormulaReplacerPartialInterface {

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
   * @var bool
   */
  private $withInlineChildren;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGrouplabel
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
    return Formula_DefinitionsInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $original, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$original instanceof Formula_DefinitionsInterface) {
      return NULL;
    }

    $definitions = $original->getDefinitions();
    $context = $original->getContext();

    $formula = Formula_Drilldown_FromDefinitions::create(
      $definitions,
      $this->definitionToLabel,
      $this->definitionToGrouplabel,
      $this->definitionToFormula,
      $context);

    if ($this->withInlineChildren) {

      $inlineIdsLookup = new Formula_Id_DefinitionsKey(
        $definitions,
        'inline');

      $formula = Formula_Drilldown_InlineExpanded::create(
        $formula,
        $inlineIdsLookup);
    }

    return $formula;
  }
}
