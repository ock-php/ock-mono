<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Form\Common;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaBase\Formula_ValueToValueBaseInterface;
use Donquixote\OCUI\FormulaToAnything\Partial\FormulaToAnythingPartialInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

/**
 * @STA
 */
class FormatorCommon_V2V implements FormulaToAnythingPartialInterface {

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

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return 0;
  }
}
