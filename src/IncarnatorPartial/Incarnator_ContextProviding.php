<?php

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\Ock\Incarnator\Incarnator;
use Donquixote\Ock\Incarnator\Incarnator_ContextProvidingDecorator;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

class Incarnator_ContextProviding extends IncarnatorPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function incarnate(FormulaInterface $formula, string $interface, IncarnatorInterface $incarnator): ?object {
    if (!$formula instanceof Formula_ContextProvidingInterface) {
      return NULL;
    }

    if (!$incarnator instanceof Incarnator_ContextProvidingDecorator) {
      $incarnator = new Incarnator_ContextProvidingDecorator($incarnator);
    }

    $incarnator = $incarnator->withContext($formula->getContext());

    return Incarnator::getObject(
      $formula->getDecorated(),
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
      Formula_ContextProvidingInterface::class,
      TRUE);
  }

}
