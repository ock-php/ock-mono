<?php

declare(strict_types=1);

namespace Drupal\cu\Formula\DrupalSelect;

use Donquixote\ObCK\Formula\Id\Formula_IdInterface;

/**
 * Alternative select formula with Drupal label types.
 */
interface Formula_DrupalSelectInterface extends Formula_IdInterface {

  /**
   * Gets a grouped list of options, for form building.
   *
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
  public function idGetLabel($id);

}
