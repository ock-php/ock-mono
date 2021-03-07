<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToFormula;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

class DefinitionToFormula_Proxy implements DefinitionToFormulaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToFormula\DefinitionToFormulaInterface|null
   */
  private $instance;

  /**
   * @var callable
   */
  private $factory;

  /**
   * @param callable $factory
   */
  public function __construct(callable $factory) {
    $this->factory = $factory;
  }

  /**
   * {@inheritdoc}
   */
  public function definitionGetFormula(array $definition): FormulaInterface {

    if (NULL === $this->instance) {
      $this->instance = \call_user_func($this->factory);
    }

    return $this->instance->definitionGetFormula($definition);
  }

}
