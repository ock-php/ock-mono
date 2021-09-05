<?php
declare(strict_types=1);

namespace Donquixote\ObCK\ValueStub;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface;

class ValueStub {

  /**
   * @param \Donquixote\ObCK\Core\Formula\FormulaInterface $formula
   * @param mixed $conf
   * @param \Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\ObCK\ValueStub\ValueStubInterface
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function fromFormulaConf(FormulaInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ValueStubInterface {

    /** @var \Donquixote\ObCK\ValueStub\ValueStubInterface $object */
    $object = $scta->formula($formula, $conf, ValueStubInterface::class);

    return $object;
  }

}
