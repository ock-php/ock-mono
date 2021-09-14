<?php
declare(strict_types=1);

namespace Donquixote\Ock\Form\Common;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;
use Donquixote\Ock\IncarnatorPartial\IncarnatorPartialZeroBase;

/**
 * @STA
 */
class FormatorCommon_V2V extends IncarnatorPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function incarnate(
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
