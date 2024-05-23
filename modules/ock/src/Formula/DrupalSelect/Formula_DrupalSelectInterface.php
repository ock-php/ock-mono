<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalSelect;

use Ock\Ock\Formula\Id\Formula_IdInterface;
use Drupal\Component\Render\MarkupInterface;

/**
 * Alternative select formula with Drupal label types.
 */
interface Formula_DrupalSelectInterface extends Formula_IdInterface {

  /**
   * Gets a grouped list of options, for form building.
   *
   * @return array<string, array<string, MarkupInterface|string>>
   *   Format: $[$group_label][$option_value] = $option_label.
   *   Top-level options have $group_label === ''.
   *
   * @throws \Ock\Ock\Exception\FormulaException
   */
  public function getGroupedOptions(): array;

  /**
   * Finds a label for a given id, to be used in summaries.
   *
   * @param string|int $id
   *
   * @return string|\Drupal\Component\Render\MarkupInterface|null
   */
  public function idGetLabel(string|int $id): string|MarkupInterface|null;

}
