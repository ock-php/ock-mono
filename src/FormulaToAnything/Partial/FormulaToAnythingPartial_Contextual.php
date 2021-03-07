<?php

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_Contextual implements FormulaToAnythingPartialInterface {

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

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }

}
