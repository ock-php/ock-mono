<?php
declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Nursery\NurseryInterface;

/**
 * @see \Donquixote\Ock\Nursery\NurseryInterface
 */
interface IncarnatorInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula from which to breed a new object.
   * @param string $interface
   *   Interface for the return value.
   * @param \Donquixote\Ock\Nursery\NurseryInterface $nursery
   *   Nursery for nested breed calls.
   *
   * @return null|object
   *   An instance of $interface, or NULL to try other cradles instead.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
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
