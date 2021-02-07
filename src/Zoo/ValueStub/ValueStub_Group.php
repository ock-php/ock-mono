<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\ValueStub;

use Donquixote\OCUI\Formula\Group\Formula_GroupInterface;
use Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface;

class ValueStub_Group implements ValueStubInterface {

  /**
   * @var array|\Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface[]
   */
  private $itemValueStubs;

  /**
   * @SCTA
   *
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $schema
   * @param mixed $conf
   * @param \Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return self|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
   */
  public static function createFromGroupFormula(Formula_GroupInterface $schema, $conf, FormulaConfToAnythingInterface $scta): ?ValueStub_Group {
    $itemStubs = self::createItemStubs($schema, $conf, $scta);
    return new self($itemStubs);
  }

  /**
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $groupFormula
   * @param mixed $conf
   * @param \Donquixote\OCUI\FormulaConfToAnything\FormulaConfToAnythingInterface $scta
   *
   * @return \Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface[]|null
   *
   * @throws \Donquixote\OCUI\Exception\FormulaToAnythingException
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
   * @param \Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface[] $itemValueStubs
   */
  protected function __construct(array $itemValueStubs) {
    $this->itemValueStubs = $itemValueStubs;
  }

  /**
   * @return array|\Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface[]
   */
  public function getItems(): array {
    return $this->itemValueStubs;
  }
}
