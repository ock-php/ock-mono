<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\ValueStub;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface;

class ValueStub {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $formula
   * @param mixed $conf
   * @param \Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function fromFormulaConf(FormulaInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?ValueStubInterface {

    $object = $scta->formula($formula, $conf, ValueStubInterface::class);

    if (NULL === $object || !$object instanceof ValueStubInterface) {
      return NULL;
    }

    return $object;
  }

}
