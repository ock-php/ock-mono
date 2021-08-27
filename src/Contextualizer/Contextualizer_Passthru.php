<?php

namespace Donquixote\ObCK\Contextualizer;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ReflectionKit\Context\ContextInterface;

class Contextualizer_Passthru implements ContextualizerInterface {

  /**
   * @var \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  private $formula;

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   */
  protected function __construct(FormulaInterface $formula) {
    $this->formula = $formula;
  }

  /**
   * {@inheritdoc}
   */
  public function contextGetFormula(?ContextInterface $context): string {
    // TODO: Implement contextGetFormula() method.
  }

}
