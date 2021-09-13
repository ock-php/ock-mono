<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Ock\TextLookup\TextLookup;
use Donquixote\Ock\TextLookup\TextLookupInterface;
use Donquixote\Ock\Text\TextInterface;

/**
 * Select formula using TextLookup* objects for option labels and group labels.
 */
abstract class Formula_Select_LabelLookupBase extends Formula_Select_BufferedBase {

  /**
   * @var \Donquixote\Ock\TextLookup\TextLookupInterface
   */
  private TextLookupInterface $labelLookup;

  /**
   * @var \Donquixote\Ock\TextLookup\TextLookupInterface
   */
  private TextLookupInterface $groupLabelLookup;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $option_label_lookup
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $group_label_lookup
   */
  public function __construct(
    TextLookupInterface $option_label_lookup,
    TextLookupInterface $group_label_lookup
  ) {
    $this->labelLookup = $option_label_lookup;
    $this->groupLabelLookup = $group_label_lookup;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
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
      $this->labelLookup);
    $group_labels = TextLookup::idMapGetLabels(
      $grouped_ids_map,
      $this->groupLabelLookup);
  }

  /**
   * Gets grouped ids without labels.
   *
   * @return true[][]
   *   Format: $[$group_id][$id] = TRUE.
   */
  abstract protected function getGroupedIdsMap(): array;

}
