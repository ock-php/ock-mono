<?php

declare(strict_types=1);

namespace Drupal\ock\Formula\DrupalSelect;

use Donquixote\Ock\Formula\Id\Formula_IdInterface;
use Donquixote\Ock\Formula\IdToLabel\Formula_IdToLabelInterface;
use Drupal\Component\Render\MarkupInterface;

/**
 * Alternative select formula with Drupal label types.
 */
interface Formula_DrupalSelectInterface extends Formula_IdInterface {

  /**
   * Gets a grouped list of options, for form building.
   *
   * @return array<string, array<
   * @return string[][]|\Drupal\Component\Render\MarkupInterface[][]
   *   Format: $[$group_label][$option_value] = $option_label.
   *   Top-level options have $group_label === ''.
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
