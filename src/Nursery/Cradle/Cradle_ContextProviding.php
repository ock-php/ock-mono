<?php

namespace Donquixote\Ock\Nursery\Cradle;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\ContextProviding\Formula_ContextProvidingInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

class Cradle_ContextProviding extends CradleZeroBase {

  /**
   * {@inheritdoc}
   */
  public function breed(FormulaInterface $formula, string $interface, NurseryInterface $nursery): ?object {
    if (!$formula instanceof Formula_ContextProvidingInterface) {
      return NULL;
    }

    return $nursery
      # ->withContext($formula->getContext())
      ->breed(
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
