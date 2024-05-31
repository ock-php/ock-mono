<?php
declare(strict_types=1);

namespace Drupal\renderkit\ListFormat;

/**
 * List format that does not add any wrappers and containers.
 */
class ListFormat_Bare implements ListFormatInterface {

  /**
   * {@inheritdoc}
   */
  public function buildList(array $builds): array {
    return $builds;
  }

}
