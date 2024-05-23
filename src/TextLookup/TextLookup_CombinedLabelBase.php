<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookupInterface;

abstract class TextLookup_CombinedLabelBase implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $groupLabelLookup
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $decorated
   */
  public function __construct(
    private TextLookupInterface $groupLabelLookup,
    private TextLookupInterface $decorated,
  ) {}

  /**
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $decorated
   *
   * @return static
   */
  public function withDecoratedLabelLookup(TextLookupInterface $decorated): static {
    $clone = clone $this;
    $clone->decorated = $decorated;
    return $clone;
  }

  /**
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $groupLabelLookup
   *
   * @return static
   */
  public function withGroupLabelLookup(TextLookupInterface $groupLabelLookup): static {
    $clone = clone $this;
    $clone->groupLabelLookup = $groupLabelLookup;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetText(int|string $id): ?TextInterface {
    $groupId = explode('.', $id)[0];
    if (NULL === $groupLabel = $this->groupLabelLookup->idGetText($groupId)) {
      return NULL;
    }
    if (NULL === $decoratedLabel = $this->decorated->idGetText($id)) {
      return NULL;
    }
    return $this->combineLabels($groupLabel, $decoratedLabel);
  }

  /**
   * @param \Donquixote\Ock\Text\TextInterface $groupLabel
   * @param \Donquixote\Ock\Text\TextInterface $decoratedLabel
   *
   * @return \Donquixote\Ock\Text\TextInterface
   */
  protected function combineLabels(TextInterface $groupLabel, TextInterface $decoratedLabel): TextInterface {
    return Text::label($groupLabel, $decoratedLabel);
  }

}
