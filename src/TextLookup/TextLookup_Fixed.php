<?php

declare(strict_types=1);

namespace Donquixote\Ock\TextLookup;

/**
 * Helper object to provide labels in bulk.
 */
class TextLookup_Fixed implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
   */
  public function __construct(
    private readonly array $labels,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idsMapGetTexts(array $ids_map): array {
    return array_intersect_key($this->labels, $ids_map);
  }

}
