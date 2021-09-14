<?php
declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Incarnator\IncarnatorInterface;

/**
 * @see \Donquixote\Ock\Incarnator\IncarnatorInterface
 */
interface IncarnatorPartialInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula from which to breed a new object.
   * @param string $interface
   *   Interface for the return value.
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *   Nursery for nested breed calls.
   *
   * @return null|object
   *   An instance of $interface, or NULL to try other partials instead.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   Malfunction in a formula replacer.
   */
  public function incarnate(
    FormulaInterface $formula,
    string $interface,
    IncarnatorInterface $incarnator
  ): ?object;

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
