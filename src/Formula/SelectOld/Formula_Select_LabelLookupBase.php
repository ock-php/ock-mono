<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\SelectOld;

use Donquixote\Ock\Formula\Formula;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\TextLookup\TextLookup;
use Donquixote\Ock\TextLookup\TextLookupInterface;

/**
 * Select formula using TextLookup* objects for option labels and group labels.
 */
abstract class Formula_Select_LabelLookupBase extends Formula_Select_BufferedBase {

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $labelLookup
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $groupLabelLookup
   */
  public function __construct(
    private readonly TextLookupInterface $labelLookup,
    private readonly TextLookupInterface $groupLabelLookup,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function idGetLabel(string|int $id): ?TextInterface {
    return $this->idIsKnown($id)
      ? TextLookup::idGetLabel($id, $this->labelLookup)
      : NULL;
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(array &$grouped_options, array &$group_labels): void {
    $grouped_ids_map = $this->getGroupedIdsMap();
    $grouped_options = TextLookup::groupedIdsMapGetLabelss(
      $grouped_ids_map,
      $this->labelLookup,
    );
    $group_labels = TextLookup::idMapGetLabels(
      $grouped_ids_map,
      $this->groupLabelLookup,
    );
  }

  /**
   * Gets grouped ids without labels.
   *
   * @return true[][]
   *   Format: $[$group_id][$id] = TRUE.
   */
  abstract protected function getGroupedIdsMap(): array;

}
