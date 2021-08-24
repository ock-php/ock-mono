<?php
declare(strict_types=1);

namespace Donquixote\ObCK\IdToFormula;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface;
use Donquixote\ObCK\Exception\FormulaCreationException;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;

class IdToFormula_FromDefinitionMap implements IdToFormulaInterface {

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface
   */
  private $definitionToFormula;

  /**
   * @var \Donquixote\ObCK\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\ObCK\Defmap\DefinitionToFormula\DefinitionToFormulaInterface $definitionToFormula
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   */
  public function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToFormulaInterface $definitionToFormula,
    CfContextInterface $context = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->definitionToFormula = $definitionToFormula;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetFormula($id): ?FormulaInterface {

    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }

    try {
      return $this->definitionToFormula->definitionGetFormula($definition, $this->context);
    }
    catch (FormulaCreationException $e) {
      // @todo Maybe report this somewhere?
      return NULL;
    }
  }
}
