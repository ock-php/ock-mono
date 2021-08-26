<?php

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_ContextProviding extends FormulaToAnythingPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function formula(FormulaInterface $formula, string $interface, FormulaToAnythingInterface $helper): ?object {
    if (!$formula instanceof Formula_ContextProvidingInterface) {
      return NULL;
    }

    return $helper
      # ->withContext($formula->getContext())
      ->formula(
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
