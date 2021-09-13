<?php
declare(strict_types=1);

namespace Donquixote\Ock\Nursery\Cradle;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

/**
 * @STA
 */
class Cradle_Identity implements CradleInterface {

  /**
   * {@inheritdoc}
   */
  public function breed(FormulaInterface $formula, string $interface, NurseryInterface $nursery): ?object {
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
