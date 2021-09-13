<?php

declare(strict_types=1);

namespace Donquixote\Ock\TextLookupMap;

use Donquixote\Ock\TextLookup\TextLookupInterface;

class TextLookupMap_Fixed implements TextLookupMapInterface {

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
  public function idGetTextLookup(string $id): ?TextLookupInterface {
    return $this->lookups[$id] ?? NULL;
  }

}
