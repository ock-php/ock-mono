<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Nursery\Cradle;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Nursery\NurseryInterface;

/**
 * @see \Donquixote\ObCK\Nursery\NurseryInterface
 */
interface CradleInterface {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   *   Formula from which to breed a new object.
   * @param string $interface
   *   Interface for the return value.
   * @param \Donquixote\ObCK\Nursery\NurseryInterface $nursery
   *   Nursery for nested breed calls.
   *
   * @return null|object
   *   An instance of $interface, or NULL to try other cradles instead.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   *   Malfunction in a formula replacer.
   */
  public function breed(
    FormulaInterface $formula,
    string $interface,
    NurseryInterface $nursery): ?object;

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
