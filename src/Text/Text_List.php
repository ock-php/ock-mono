<?php

declare(strict_types=1);

namespace Donquixote\Ock\Text;

class Text_List extends Text_ListBase {

  private string $tag;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\Text\TextInterface[] $items
   * @param string $tag
   */
  public function __construct(array $items, string $tag = 'ul') {
    parent::__construct($items);
    $this->tag = $tag;
  }

  /**
   * {@inheritdoc}
   */
  protected function joinParts(array $parts): string {
    $result = '';
    foreach ($parts as $part) {
      $result .= "<li>$part</li>";
    }
    return $result === ''
      ? ''
      : "<$this->tag>$result</$this->tag>";
  }

}
