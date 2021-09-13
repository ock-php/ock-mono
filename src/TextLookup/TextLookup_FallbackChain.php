<?php

declare(strict_types=1);

namespace Donquixote\Ock\TextLookup;

/**
 * Helper object to provide labels in bulk.
 */
class TextLookup_FallbackChain implements TextLookupInterface {

  /**
   * @var \Donquixote\Ock\TextLookup\TextLookupInterface[]
   */
  private array $lookups;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface[] $lookups
   */
  public function __construct(array $lookups) {
    $this->lookups = $lookups;
  }

  /**
   * {@inheritdoc}
   */
  public function idsMapGetTexts(array $ids_map): array {
    $labels = [];
    $remaining = $ids_map;
    foreach ($this->lookups as $lookup) {
      $new_labels = $lookup->idsMapGetTexts($remaining);
      $remaining = array_diff_key($remaining, $new_labels);
      $labels += $new_labels;
    }
    return $labels;
  }

}
