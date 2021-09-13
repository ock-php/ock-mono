<?php

declare(strict_types=1);

namespace Donquixote\Ock\TextLookup;

/**
 * Helper object to provide labels in bulk.
 */
class TextLookup_Fixed implements TextLookupInterface {

  /**
   * @var \Donquixote\Ock\Text\TextInterface[]
   */
  private array $labels;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface[] $labels
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
