<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\TypeToFormula\TypeToFormulaInterface;
use Donquixote\OCUI\Formula\Iface\Formula_IfaceInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

/**
 * @STA
 */
class FormulaToAnythingPartial_Identity implements FormulaToAnythingPartialInterface {

  /**
   * {@inheritdoc}
   */
  public function formula(FormulaInterface $formula, string $interface, FormulaToAnythingInterface $helper): ?object {
    return ($formula instanceof $interface)
      ? $formula
      : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function providesResultType(string $resultInterface): bool {
    return \is_a(
      $resultInterface,
      FormulaInterface::class,
      TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function acceptsFormulaClass(string $formulaClass): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function getSpecifity(): int {
    return -100;
  }

}
