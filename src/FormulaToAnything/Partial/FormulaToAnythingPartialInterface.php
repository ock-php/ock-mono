<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface;

/**
 * @see \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface
 */
interface FormulaToAnythingPartialInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param string $interface
   * @param \Donquixote\OCUI\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   *   Malfunction in a formula replacer.
   */
  public function formula(
    FormulaInterface $formula,
    string $interface,
    FormulaToAnythingInterface $helper): ?object;

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType(string $resultInterface): bool;

  /**
   * @param string $formulaClass
   *
   * @return bool
   */
  public function acceptsFormulaClass(string $formulaClass): bool;

  /**
   * @return int
   */
  public function getSpecifity(): int;

}
