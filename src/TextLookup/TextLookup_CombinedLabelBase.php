<?php

declare(strict_types=1);

namespace Drupal\renderkit\TextLookup;

use Donquixote\Adaptism\Attribute\Parameter\GetService;
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
    private readonly TextLookupInterface $groupLabelLookup,
    private readonly TextLookupInterface $decorated,
  ) {}

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
