<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\ObCK\Exception\FormulaCreationException;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;

class IdToFormula_FromDefinitions implements IdToFormulaInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @var \Donquixote\ObCK\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param array[] $definitions
   * @param \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
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
