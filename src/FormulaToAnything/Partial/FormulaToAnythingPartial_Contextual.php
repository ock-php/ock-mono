<?php

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_Contextual extends FormulaToAnythingPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function formula(FormulaInterface $formula, string $interface, FormulaToAnythingInterface $helper): ?object {

    if (!$formula instanceof Formula_ContextualInterface) {
      return NULL;
    }

    return $helper
      ->formula(
        $formula->getDecorated($helper->getContext()),
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
