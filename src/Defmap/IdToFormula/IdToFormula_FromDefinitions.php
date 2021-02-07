<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\IdToFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\OCUI\Exception\FormulaCreationException;
use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;

class IdToFormula_FromDefinitions implements IdToFormulaInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array[] $definitions
   * @param \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   */
  public function __construct(
    array $definitions,
    DefinitionToFormulaInterface $definitionToFormula,
    CfContextInterface $context = NULL
  ) {
    $this->definitions = $definitions;
    $this->definitionToFormula = $definitionToFormula;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {

    if (!isset($this->definitions[$id])) {
      return NULL;
    }

    try {
      return $this->definitionToFormula->definitionGetFormula(
        $this->definitions[$id],
        $this->context);
    }
    catch (FormulaCreationException $e) {
      // @todo Maybe report this somewhere?
      return NULL;
    }
  }
}
