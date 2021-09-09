<?php

declare(strict_types=1);

namespace Donquixote\ObCK\TextLookup;

/**
 * Helper object to provide labels in bulk.
 */
class TextLookup_Fixed implements TextLookupInterface {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $labels;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface[] $labels
   */
  public function __construct(array $labels) {
    $this->labels = $labels;
  }

  /**
   * {@inheritdoc}
   */
  public function idsMapGetTexts(array $ids_map): array {
    return array_intersect_key($this->labels, $ids_map);
  }

}
