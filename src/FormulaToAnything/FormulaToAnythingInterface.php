<?php
declare(strict_types=1);

namespace Donquixote\OCUI\FormulaToAnything;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface FormulaToAnythingInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or
   *   NULL, if no adapter can be found.
   */
  public function schema(FormulaInterface $schema, string $interface);

}
