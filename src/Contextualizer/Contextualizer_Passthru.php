<?php

namespace Donquixote\Ock\Contextualizer;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\ReflectionKit\Context\ContextInterface;

class Contextualizer_Passthru implements ContextualizerInterface {

  /**
   * @var \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  private $formula;

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   */
  protected function __construct(FormulaInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function contextGetFormula(?ContextInterface $context): FormulaInterface {
    return $this->formula;
  }

}
