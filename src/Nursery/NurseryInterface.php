<?php
declare(strict_types=1);

namespace Donquixote\Ock\Nursery;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface NurseryInterface {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   *   Formula from which to breed a new object.
   * @param string $interface
   *   Interface for the return value.
   * @return object
   *   An instance of $interface.
   *
   * @throws \Donquixote\Ock\Exception\FormulaToAnythingException
   *   Object cannot be created for the given formula.
   */
  public function breed(FormulaInterface $formula, string $interface): object;

  /**
   * Gets a cache id.
   *
   * @return string
   */
  public function getCacheId(): string;

}
