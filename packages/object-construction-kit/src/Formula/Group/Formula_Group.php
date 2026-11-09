<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\Group;

use Ock\Ock\Formula\Group\Item\GroupFormulaItemInterface;

class Formula_Group implements Formula_GroupInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\Formula\Group\Item\GroupFormulaItemInterface[] $items
   */
  public function __construct(
    private readonly array $items,
  ) {
    // Use array_values(): With mixed keys, an integer key after a string key
    // would cause an error, "Cannot use positional argument after named
    // argument during unpacking".
    self::validateItems(...array_values($items));
  }

  /**
   * {@inheritdoc}
   */
  public function getItems(): array {
    return $this->items;
  }

  /**
   * Validates item types, through the parameter type.
   *
   * @param \Ock\Ock\Formula\Group\Item\GroupFormulaItemInterface ...$items
   *   Group items.
   */
  private static function validateItems(GroupFormulaItemInterface ...$items): void {}

}
