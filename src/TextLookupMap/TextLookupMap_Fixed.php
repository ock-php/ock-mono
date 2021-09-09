<?php

declare(strict_types=1);

namespace Donquixote\ObCK\TextLookupMap;

use Donquixote\ObCK\TextLookup\TextLookupInterface;

class TextLookupMap_Fixed implements TextLookupMapInterface {

  /**
   * @var \Donquixote\ObCK\TextLookup\TextLookupInterface[]
   */
  private array $lookups;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\TextLookup\TextLookupInterface[] $lookups
   */
  public function __construct(array $lookups) {
    $this->lookups = $lookups;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetTextLookup(string $id): ?TextLookupInterface {
    return $this->lookups[$id] ?? NULL;
  }

}
