<?php

declare(strict_types=1);

namespace Donquixote\Ock\Incarnator;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface IncarnatorInterface {

  /**
   * Attempts to "incarnate" a formula as another type.
   *
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula from which to incarnate a new object.
   * @param string $interface
   *   Interface for the return value.
   *
   * @return object
   *   An instance of $interface.
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   *   Misbehaving incarnator, or unsupported formula.
   */
  public function incarnate(FormulaInterface $formula, string $interface): object;

  /**
   * Gets a cache id.
   *
   * @return string
   */
  public function getCacheId(): string;

}
