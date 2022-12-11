<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Group\Item;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Text\TextInterface;

class GroupFormulaItem_Callback implements GroupFormulaItemInterface {

  public function __construct(
    private readonly TextInterface $label,
    private readonly array $keys,
    private readonly mixed $callback,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getLabel(): TextInterface {
    return $this->label;
  }

  /**
   * {@inheritdoc}
   */
  public function dependsOnKeys(): array {
    return $this->keys;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormula(array $args = []): FormulaInterface {
    return ($this->callback)(...$args);
  }

}
