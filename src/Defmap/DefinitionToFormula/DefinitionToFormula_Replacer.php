<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface;

class DefinitionToFormula_Replacer implements DefinitionToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $decorated;

  /**
   * @var \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface
   */
  private $replacer;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $decorated
   * @param \Donquixote\OCUI\FormulaReplacer\FormulaReplacerInterface $replacer
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
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaCreationException
   */
  public function definitionGetFormula(
    array $definition,
    CfContextInterface $context = NULL
  ): FormulaInterface {
    $schema = $this->decorated->definitionGetFormula(
      $definition,
      $context);

    if (NULL !== $replacement = $this->replacer->schemaGetReplacement($schema)) {
      $schema = $replacement;
    }

    return $schema;
  }
}
