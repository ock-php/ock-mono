<?php

declare(strict_types=1);

namespace Donquixote\Ock\TextLookup;

use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;

/**
 * Helper object to provide labels in bulk.
 */
class TextLookup_EllipsisIfDecorator implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $decorated
   * @param callable(string|int): bool $idNeedsEllipsis
   */
  public function __construct(
    private readonly TextLookupInterface $decorated,
    private readonly mixed $idNeedsEllipsis,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idGetText(int|string $id): ?TextInterface {
    $text = $this->decorated->idGetText($id);
    if ($text === NULL) {
      return $text;
    }
    if (($this->idNeedsEllipsis)($id)) {
      $text = Text::sprintf('%s…', $text);
    }
    return $text;
  }

}
