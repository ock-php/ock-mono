<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\ObCK\Defmap\IdToDefinition\IdToDefinitionInterface;
use Donquixote\ObCK\Exception\FormulaCreationException;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;

class IdToFormula_ViaDefinitionX implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @var \Donquixote\ObCK\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\ObCK\Defmap\IdToDefinition\IdToDefinitionInterface $idToDefinition
   * @param \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @throws \Donquixote\ObCK\Exception\FormulaCreationException
   */
  public function __construct(
    IdToDefinitionInterface $idToDefinition,
    DefinitionToFormulaInterface $definitionToFormula,
    CfContextInterface $context = NULL
  ) {
    $this->idToDefinition = $idToDefinition;
    $this->definitionToFormula = $definitionToFormula;
    $definitionToFormula->definitionGetFormula([], $context);
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {

    $definition = $this->idToDefinition->idGetDefinition($id);

    if (NULL === $definition) {
      return NULL;
    }

    try {
      return $this->definitionToFormula->definitionGetFormula($definition, $this->context);
    }
    catch (FormulaCreationException $e) {
      // @todo Report this in watchdog?
      return NULL;
    }
  }
}
