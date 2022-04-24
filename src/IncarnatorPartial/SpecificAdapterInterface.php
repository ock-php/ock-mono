<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartial;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface;

/**
 * Partial incarnator, that supports specific types only.
 *
 * @see \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface
 */
interface SpecificAdapterInterface {

  /**
   * Attempts to "incarnate" a formula as another type.
   *
   * @template T
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula from which to incarnate a new object.
   * @param class-string<T> $interface
   *   Interface for the return value.
   * @param \Donquixote\Adaptism\UniversalAdapter\UniversalAdapterInterface $universalAdapter
   *   Top-level incarnator that supports a wide range of formulas and
   *   destination types.
   *
   * @return T|null
   *   An instance of $interface, or NULL to try other partials instead.
   *
   * @throws \Donquixote\Adaptism\Exception\AdapterException
   *   Malfunction in an incarnator.
   */
  public function incarnate(
    FormulaInterface $formula,
    string $interface,
    UniversalAdapterInterface $universalAdapter
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
