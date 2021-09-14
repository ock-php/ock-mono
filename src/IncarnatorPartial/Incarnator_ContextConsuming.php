<?php

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\Incarnator_ContextProvidingDecorator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class Incarnator_ContextConsuming extends IncarnatorPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function incarnate(FormulaInterface $formula, string $interface, IncarnatorInterface $incarnator): ?object {

    if (!$formula instanceof Formula_ContextualInterface) {
      return NULL;
    }

    $context = $incarnator instanceof Incarnator_ContextProvidingDecorator
      ? $incarnator->getContext()
      : NULL;

    return Incarnator::getObject(
      $formula->getDecorated($context),
      $interface,
      $incarnator);
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
