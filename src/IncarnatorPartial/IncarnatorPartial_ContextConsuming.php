<?php

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Contextual\Formula_ContextualInterface;
use Donquixote\Ock\FormulaAdapter;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

class IncarnatorPartial_ContextConsuming extends SpecificAdapterZeroBase {

  /**
   * {@inheritdoc}
   */
  public function incarnate(FormulaInterface $formula, string $interface, UniversalAdapterInterface $universalAdapter): ?object {

    if (!$formula instanceof Formula_ContextualInterface) {
      return NULL;
    }

    $context = $universalAdapter->getContext();

    return $universalAdapter->adapt($formula->getDecorated($context), $interface);
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
