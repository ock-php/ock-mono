<?php

declare(strict_types=1);

namespace Donquixote\Ock\TextLookup;

/**
 * Helper object to provide labels in bulk.
 *
 * It is recommended to call this indirectly via LabelLookup::*().
 *
 */
interface TextLookupInterface {

  /**
   * Attempts to find labels for ids.
   *
   * @param mixed[] $ids_map
   *   Format: $[$id] = $_anything.
   *
   * @return \Donquixote\Ock\Text\TextInterface[]
   *   Format: $[$id] = $text.
   *   This only includes ids for which a label was found.
   *   The order is not guaranteed.
   */
  public function idsMapGetTexts(array $ids_map): array;

}
