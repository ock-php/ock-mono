<?php

namespace Donquixote\Ock\Nursery\Cradle;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

class Cradle_Contextual extends CradleZeroBase {

  /**
   * {@inheritdoc}
   */
  public function breed(FormulaInterface $formula, string $interface, NurseryInterface $nursery): ?object {

    if (!$formula instanceof Formula_ContextualInterface) {
      return NULL;
    }

    return $nursery
      ->breed(
        $formula->getDecorated($nursery->getContext()),
        $interface);
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return is_a(
      $formulaClass,
      Formula_ContextualInterface::class,
      TRUE);
  }

}
