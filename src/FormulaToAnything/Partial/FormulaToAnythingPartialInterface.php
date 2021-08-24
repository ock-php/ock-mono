<?php
declare(strict_types=1);

namespace Donquixote\ObCK\FormulaToAnything\Partial;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface;

/**
 * @see \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface
 */
interface FormulaToAnythingPartialInterface {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param string $interface
   * @param \Donquixote\ObCK\FormulaToAnything\FormulaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
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
