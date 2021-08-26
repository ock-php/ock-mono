<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Form\Common;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;
use Donquixote\ObCK\FormulaToAnything\Partial\FormulaToAnythingPartialZeroBase;

/**
 * @STA
 */
class FormatorCommon_V2V extends FormulaToAnythingPartialZeroBase {

  /**
   * {@inheritdoc}
   */
  public function formula(
    FormulaInterface $formula,
    string $interface,
    FormulaToAnythingInterface $helper
  ): ?object {

    if (!$formula instanceof Formula_ValueToValueBaseInterface) {
      return NULL;
    }

    return $helper->formula($formula->getDecorated(), $interface);
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
