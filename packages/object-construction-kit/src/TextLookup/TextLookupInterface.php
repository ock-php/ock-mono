<?php

declare(strict_types=1);

namespace Ock\Ock\TextLookup;

use Ock\Text\TextInterface;

/**
 * Helper object to provide labels in bulk.
 *
 * It is recommended to call this indirectly via LabelLookup::*().
 */
interface TextLookupInterface {

  /**
   * @param string|int $id
   *
   * @return \Ock\Text\TextInterface|null
   *   The text, or NULL if not found or not labeled.
   */
  public function idGetText(string|int $id): ?TextInterface;

}
