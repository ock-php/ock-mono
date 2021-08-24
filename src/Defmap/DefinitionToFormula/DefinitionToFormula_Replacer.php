<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;

class DefinitionToFormula_Replacer implements DefinitionToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $decorated
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   */
  public function __construct(
    DefinitionToFormulaInterface $decorated,
    FormulaReplacerInterface $replacer
  ) {
    $this->decorated = $decorated;
    $this->replacer = $replacer;
  }

  /**
   * @param array $definition
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaCreationException
   */
  public function definitionGetFormula(array $definition): FormulaInterface {
    $formula = $this->decorated->definitionGetFormula(
      $definition);

    if (NULL !== $replacement = $this->replacer->formulaGetReplacement($formula)) {
      $formula = $replacement;
    }

    return $formula;
  }
}
