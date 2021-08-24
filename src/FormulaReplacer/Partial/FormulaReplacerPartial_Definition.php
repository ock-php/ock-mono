<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaReplacer\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\ObCK\Formula\Definition\Formula_DefinitionInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

class FormulaReplacerPartial_Definition implements FormulaReplacerPartialInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   */
  public function __construct(DefinitionToFormulaInterface $definitionToFormula) {
    $this->definitionToFormula = $definitionToFormula;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFormulaClass(): string {
    return Formula_DefinitionInterface::class;
  }

  /**
   * {@inheritdoc}
   */
  public function formulaGetReplacement(FormulaInterface $formula, FormulaReplacerInterface $replacer): ?FormulaInterface {

    if (!$formula instanceof Formula_DefinitionInterface) {
      return NULL;
    }

    try {
      $formula = $this->definitionToFormula->definitionGetFormula(
        $formula->getDefinition(),
        $formula->getContext());
    }
    catch (\Exception $e) {
      // @todo Allow throwing exceptions? Log the problem somewhere? BrokenFormula?
      return NULL;
    }

    if (NULL !== $replacement = $replacer->formulaGetReplacement($formula)) {
      $formula = $replacement;
    }

    return $formula;
  }
}
