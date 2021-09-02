<?php
declare(strict_types=1);

namespace Donquixote\ObCK\ValueStub;

use Donquixote\ObCK\Formula\Group\Formula_GroupInterface;
use Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface;

class ValueStub_Group implements ValueStubInterface {

  /**
   * @var array|\Donquixote\ObCK\ValueStub\ValueStubInterface[]
   */
  private $itemValueStubs;

  /**
   * @SCTA
   *
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $formula
   * @param mixed $conf
   * @param \Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return self|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
   */
  public static function createFromGroupFormula(Formula_GroupInterface $formula, $conf, FormulaConfToAnythingInterface $scta): ?ValueStub_Group {
    $itemStubs = self::createItemStubs($formula, $conf, $scta);
    return new self($itemStubs);
  }

  /**
   * @param \Donquixote\ObCK\Formula\Group\Formula_GroupInterface $groupFormula
   * @param mixed $conf
   * @param \Donquixote\ObCK\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\ObCK\ValueStub\ValueStubInterface[]|null
   *
   * @throws \Donquixote\ObCK\Exception\FormulaToAnythingException
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
   * @param \Donquixote\ObCK\ValueStub\ValueStubInterface[] $itemValueStubs
   */
  protected function __construct(array $itemValueStubs) {
    $this->itemValueStubs = $itemValueStubs;
  }

  /**
   * @return array|\Donquixote\ObCK\ValueStub\ValueStubInterface[]
   */
  public function getItems(): array {
    return $this->itemValueStubs;
  }
}
