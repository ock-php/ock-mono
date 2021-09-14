<?php

declare(strict_types=1);

namespace Donquixote\Ock\ValueStub;

use Donquixote\Ock\Formula\Group\Formula_GroupInterface;
use Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface;

class ValueStub_Group implements ValueStubInterface {

  /**
   * @var array|\Donquixote\Ock\ValueStub\ValueStubInterface[]
   */
  private $itemValueStubs;

  /**
   * @SCTA
   *
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $formula
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return self|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  public static function createFromGroupFormula(Formula_GroupInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?ValueStub_Group {
    $itemStubs = self::createItemStubs($formula, $conf, $scta);
    return new self($itemStubs);
  }

  /**
   * @param \Donquixote\Ock\Formula\Group\Formula_GroupInterface $groupFormula
   * @param mixed $conf
   * @param \Donquixote\Ock\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\Ock\ValueStub\ValueStubInterface[]|null
   *
   * @throws \Donquixote\Ock\Exception\IncarnatorException
   */
  private static function createItemStubs(Formula_GroupInterface $groupFormula, $conf, FormulaConfToAnythingInterface $scta): ?array {

    $itemValueStubs = [];
    foreach ($groupFormula->getItemFormulas() as $k => $itemFormula) {

      $itemValueStub = ValueStub::fromFormulaConf($itemFormula, $conf, $scta);

      if (NULL === $itemValueStub) {
        return NULL;
      }

      $itemValueStubs[$k] = $itemValueStub;
    }

    return $itemValueStubs;
  }

  /**
   * @param \Donquixote\Ock\ValueStub\ValueStubInterface[] $itemValueStubs
   */
  protected function __construct(array $itemValueStubs) {
    $this->itemValueStubs = $itemValueStubs;
  }

  /**
   * @return array|\Donquixote\Ock\ValueStub\ValueStubInterface[]
   */
  public function getItems(): array {
    return $this->itemValueStubs;
  }
}
