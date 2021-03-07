<?php
declare(strict_types=1);

namespace Donquixote\OCUI\IdToFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface;
use Donquixote\OCUI\Exception\FormulaCreationException;
use Donquixote\OCUI\IdToFormula\IdToFormulaInterface;

class IdToFormula_ViaDefinition implements IdToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface
   */
  private $idToDefinition;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\OCUI\Defmap\IdToDefinition\IdToDefinitionInterface $idToDefinition
   * @param \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   */
  public function __construct(
    IdToDefinitionInterface $idToDefinition,
    DefinitionToFormulaInterface $definitionToFormula,
    CfContextInterface $context = NULL
  ) {
    $this->idToDefinition = $idToDefinition;
    $this->definitionToFormula = $definitionToFormula;
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
