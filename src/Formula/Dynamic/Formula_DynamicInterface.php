<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Dynamic;

use Donquixote\Ock\Core\Formula\FormulaInterface;

interface Formula_DynamicInterface extends FormulaInterface {

  /**
   * @return list<string>
   *   Keys to look up in group config.
   */
  public function getKeys(): array;

  /**
   * @param mixed[] $values
   *   Configuration values from the group config corresponding to the keys.
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   *   Dynamic formula.
   *
   * @throws \Donquixote\Ock\Exception\FormulaException
   *   Failure to initialize the formula.
   */
  public function getFormula(array $values): FormulaInterface;

}
