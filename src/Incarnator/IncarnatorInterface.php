<?php

declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Context\CfContextInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;

interface IncarnatorInterface {

  /**
   * Attempts to "incarnate" a formula as another type.
   *
   * @template T
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula from which to incarnate a new object.
   * @param class-string<T> $interface
   *   Interface for the return value.
   * @param \Donquixote\Ock\Incarnator\IncarnatorInterface $incarnator
   *   Top-level incarnator that supports a wide range of formulas and
   *   destination types.
   *
   * @return T
   *   An instance of $interface.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   Misbehaving incarnator, or unsupported formula.
   */
  public function incarnate(
    FormulaInterface $formula,
    string $interface,
    IncarnatorInterface $incarnator,
  ): object;

  /**
   * Gets a cache id.
   *
   * @return string
   */
  public function getCacheId(): string;

  /**
   * Immutable setter. Sets a context.
   *
   * @param \Donquixote\Ock\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return static
   */
  public function withContext(?CfContextInterface $context): self;

  /**
   * @return \Donquixote\Ock\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
