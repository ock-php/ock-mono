<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group\Item;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Text\TextInterface;

interface GroupFormulaItemInterface {

  /**
   * @return \Donquixote\Ock\Text\TextInterface
   */
  public function getLabel(): TextInterface;

  /**
   * @return string[]
   *   Other keys in the group that this formula depends on.
   */
  public function dependsOnKeys(): array;

  /**
   * @param mixed[] $args
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function getFormula(array $args = []): FormulaInterface;

}
