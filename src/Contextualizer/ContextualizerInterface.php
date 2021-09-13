<?php

namespace Donquixote\Ock\Contextualizer;

use Donquixote\ReflectionKit\Context\ContextInterface;

interface ContextualizerInterface {

  /**
   * @param \Donquixote\ReflectionKit\Context\ContextInterface|null $context
   *
   * @return string
   */
  public function contextGetFormula(?ContextInterface $context): string;

}
