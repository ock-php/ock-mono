<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Text;

use Donquixote\ObCK\Translator\TranslatorInterface;

abstract class Text_ListBase extends TextBase {

  /**
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private array $items;

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface[] $items
   */
  public function __construct(array $items) {
    Text::validate(...$items);
    $this->items = $items;
  }

  /**
   * @param \Donquixote\ObCK\Text\TextInterface $item
   *
   * @return static
   */
  public function withAdded(TextInterface $item): self {
    $clone = clone $this;
    $clone->items[] = $item;
    return $clone;
  }

  /**
   * @param \Donquixote\ObCK\Text\TextInterface $item
   *
   * @return $this
   */
  public function add(TextInterface $item): self {
    $this->items[] = $item;
    return $this;
  }

  /**
   * @param string $source
   *
   * @return $this
   */
  public function addT(string $source): self {
    $this->items[] = Text::t($source);
    return $this;
  }

  /**
   * @param string $source
   *
   * @return $this
   */
  public function addS(string $source): self {
    $this->items[] = Text::s($source);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function convert(TranslatorInterface $translator): string {
    $parts = [];
    foreach ($this->items as $item) {
      $part = $item->convert($translator);
      if ($part === '') {
        continue;
      }
      $parts[] = $part;
    }
    return $this->joinParts($parts);
  }

  /**
   * @param string[] $parts
   *
   * @return string
   */
  abstract protected function joinParts(array $parts): string;

}
