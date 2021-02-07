<?php

namespace Donquixote\OCUI\Contextualizer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Generator\GeneratorInterface;
use Donquixote\ReflectionKit\Context\ContextInterface;

class Contextualizer_Passthru implements ContextualizerInterface {

  /**
   * @var \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  private $formula;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
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
