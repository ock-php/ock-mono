<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group\Item;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Text\TextInterface;

abstract class GroupFormulaItemBase implements GroupFormulaItemInterface {

  public function __construct(
    private readonly TextInterface $label,
  ) {}

  public function getLabel(): TextInterface {
    return $this->label;
  }

}
