<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Text;

class Text_ListConcat extends Text_ListBase {

  private string $separator;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface[] $items
   */
  public function __construct(array $items, string $separator = ', ') {
    parent::__construct($items);
    $this->separator = $separator;
  }

  /**
   * {@inheritdoc}
   */
  protected function joinParts(array $parts): string {
    return implode($this->separator, $parts);
  }

}
