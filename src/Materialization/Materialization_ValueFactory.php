<?php

declare(strict_types=1);

namespace Donquixote\Ock\Materialization;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Form\Common\FormatorCommonInterface;
use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialZeroBase;

class Materialization_ValueFactory extends IncarnatorPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function breed(
    FormulaInterface $formula,
    string $interface,
    IncarnatorInterface $incarnator
  ): ?object {

    if (!$formula instanceof Formula_ValueToValueBaseInterface) {
      return NULL;
    }

    return $incarnator->incarnate($formula->getDecorated(), $interface);
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return is_a(
      $resultInterface ,
      FormatorCommonInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return is_a(
      $formulaClass,
      Formula_ValueToValueBaseInterface::class,
      TRUE);
  }

}
