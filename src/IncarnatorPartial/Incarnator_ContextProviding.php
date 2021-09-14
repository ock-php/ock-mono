<?php

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class Incarnator_ContextProviding extends IncarnatorPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function breed(FormulaInterface $formula, string $interface, IncarnatorInterface $incarnator): ?object {
    if (!$formula instanceof Formula_ContextProvidingInterface) {
      return NULL;
    }

    return $incarnator
      # ->withContext($formula->getContext())
      ->incarnate(
        $formula->getDecorated(),
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
      Formula_ContextProvidingInterface::class,
      TRUE);
  }

}
