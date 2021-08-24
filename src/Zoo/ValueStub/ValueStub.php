<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Zoo\ValueStub;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface;

class ValueStub {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param mixed $conf
   * @param \Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\ObCK\Zoo\ValueStub\ValueStubInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromFormulaConf(FormulaInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?ValueStubInterface {

    $object = $scta->formula($formula, $conf, ValueStubInterface::class);

    if (NULL === $object || !$object instanceof ValueStubInterface) {
      return NULL;
    }

    return $object;
  }

}
