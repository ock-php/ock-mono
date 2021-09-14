<?php

declare(strict_types=1);

namespace Donquixote\Ock\Text;

class Text_ListConcat extends Text_ListBase {

  private string $separator;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface[] $items
   * @param string $separator
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
