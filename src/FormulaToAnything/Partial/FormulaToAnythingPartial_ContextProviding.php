<?php

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

class FormulaToAnythingPartial_ContextProviding implements FormulaToAnythingPartialInterface {

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

  public function providesResultType(string $resultInterface): bool {
    return TRUE;
  }

  public function acceptsFormulaClass(string $formulaClass): bool {
    return is_a(
      $formulaClass,
      Formula_ContextProvidingInterface::class,
      TRUE);
  }

  public function getSpecifity(): int {
    return 0;
  }

}
