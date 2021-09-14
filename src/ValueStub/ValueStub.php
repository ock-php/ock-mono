<?php

declare(strict_types=1);

namespace Donquixote\Ock\ValueStub;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface;

class ValueStub {

  /**
   * @param \Donquixote\Ock\Core\Formula\FormulaInterface $formula
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Ock\ValueStub\ValueStubInterface
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function fromFormulaConf(FormulaInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ValueStubInterface {

    /** @var \Donquixote\Ock\ValueStub\ValueStubInterface $object */
    $object = $scta->formula($formula, $conf, ValueStubInterface::class);

    return $object;
  }

}
