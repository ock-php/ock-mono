<?php

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\ContextProviding\Formula_ContextProvidingInterface;

class IncarnatorPartial_ContextProviding extends SpecificAdapterZeroBase {

  /**
   * {@inheritdoc}
   */
  public function incarnate(FormulaInterface $formula, string $interface, UniversalAdapterInterface $universalAdapter): ?object {
    if (!$formula instanceof Formula_ContextProvidingInterface) {
      return NULL;
    }

    $universalAdapter = $universalAdapter->withContext($formula->getContext());

    return $universalAdapter->adapt($formula->getDecorated(), $interface);
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
