<?php

namespace Donquixote\Ock\Contextualizer;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\ReflectionKit\Context\ContextInterface;

interface ContextualizerInterface {

  /**
   * @param \Donquixote\ReflectionKit\Context\ContextInterface|null $context
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function contextGetFormula(?ContextInterface $context): FormulaInterface;

}
