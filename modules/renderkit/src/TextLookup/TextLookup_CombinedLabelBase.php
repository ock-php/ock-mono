<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Ock\Ock\Text\Text;
use Ock\Ock\Text\TextInterface;
use Ock\Ock\TextLookup\TextLookupInterface;

abstract class TextLookup_CombinedLabelBase implements TextLookupInterface {

  /**
   * Constructor.
   *
   * @param \Ock\Ock\TextLookup\TextLookupInterface $groupLabelLookup
   * @param \Ock\Ock\TextLookup\TextLookupInterface $decorated
   */
  public function __construct(
    private TextLookupInterface $groupLabelLookup,
    private TextLookupInterface $decorated,
  ) {}

  /**
   * @param \Ock\Ock\TextLookup\TextLookupInterface $decorated
   *
   * @return static
   */
  public function withDecoratedLabelLookup(TextLookupInterface $decorated): static {
    $clone = clone $this;
    $clone->decorated = $decorated;
    return $clone;
  }

  /**
   * @param \Ock\Ock\TextLookup\TextLookupInterface $groupLabelLookup
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
   * @param \Ock\Ock\Text\TextInterface $groupLabel
   * @param \Ock\Ock\Text\TextInterface $decoratedLabel
   *
   * @return \Ock\Ock\Text\TextInterface
   */
  protected function combineLabels(TextInterface $groupLabel, TextInterface $decoratedLabel): TextInterface {
    return Text::label($groupLabel, $decoratedLabel);
  }

}
