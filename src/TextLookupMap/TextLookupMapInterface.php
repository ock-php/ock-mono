<?php

declare(strict_types=1);

namespace Donquixote\ObCK\TextLookupMap;

use Donquixote\ObCK\TextLookup\TextLookupInterface;

interface TextLookupMapInterface {

  /**
   * Gets a text lookup map specified by an id.
   *
   * @param string $id
   *
   * @return \Donquixote\ObCK\TextLookup\TextLookupInterface|null
   */
  public function idGetTextLookup(string $id): ?TextLookupInterface;

}
