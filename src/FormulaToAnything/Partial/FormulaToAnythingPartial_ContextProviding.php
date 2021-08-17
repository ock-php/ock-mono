<?php

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_ContextProviding implements FormulaToAnythingPartialInterface {

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

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }

}
