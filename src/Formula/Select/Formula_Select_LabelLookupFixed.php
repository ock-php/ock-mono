<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Select;

use Donquixote\Ock\TextLookup\TextLookupInterface;

/**
 * Select formula using TextLookup* objects for option labels and group labels.
 */
class Formula_Select_LabelLookupFixed extends Formula_Select_LabelLookupBase {

  /**
   * @var mixed[][]
   *   Format: $[$group_id][$id] = $_anything.
   */
  private array $groupedIdsMap;

  /**
   * @var mixed[]
   *   Format: $[$id] = $_anything.
   */
  private array $idsMap;

  /**
   * Constructor.
   *
   * @param mixed[][] $grouped_ids_map
   *   Format: $[$group_id][$id] = $_anything.
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $labellookup
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $groupLabelProvider
   */
  public function __construct(array $grouped_ids_map, TextLookupInterface $labellookup, TextLookupInterface $groupLabelProvider) {
    $this->groupedIdsMap = $grouped_ids_map;
    $this->idsMap = array_replace(...$grouped_ids_map);
    parent::__construct($labellookup, $groupLabelProvider);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->idsMap[$id]);
  }

  /**
   * {@inheritdoc}
   */
  protected function getGroupedIdsMap(): array {
    return $this->groupedIdsMap;
  }

}
