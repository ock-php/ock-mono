<?php

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class Incarnator_Contextual extends IncarnatorPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function breed(FormulaInterface $formula, string $interface, IncarnatorInterface $nursery): ?object {

    if (!$formula instanceof Formula_ContextualInterface) {
      return NULL;
    }

    return $nursery
      ->incarnate(
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
