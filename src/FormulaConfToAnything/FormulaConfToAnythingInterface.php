<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaConfToAnything;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface FormulaConfToAnythingInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param mixed $conf
   * @param string $interface
   *
   * @return object
   *   An instance of $interface.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public function schema(FormulaInterface $schema, $conf, string $interface): object;

}
