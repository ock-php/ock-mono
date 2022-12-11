<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\SelectOld;

use Donquixote\Ock\TextLookup\TextLookupInterface;

/**
 * Select formula using TextLookup* objects for option labels and group labels.
 */
class Formula_Select_LabelLookupFixed extends Formula_Select_LabelLookupBase {

  /**
   * @var mixed[]
   *   Format: $[$id] = $_anything.
   */
  private array $idsMap;

  /**
   * Constructor.
   *
   * @param mixed[][] $groupedIdsMap
   *   Format: $[$group_id][$id] = $_anything.
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $labelLookup
   * @param \Donquixote\Ock\TextLookup\TextLookupInterface $groupLabelProvider
   */
  public function __construct(
    private readonly array $groupedIdsMap,
    TextLookupInterface $labelLookup,
    TextLookupInterface $groupLabelProvider,
  ) {
    $this->idsMap = array_replace(...$groupedIdsMap);
    parent::__construct($labelLookup, $groupLabelProvider);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown(string|int $id): bool {
    return isset($this->idsMap[$id]);
  }

  /**
   * {@inheritdoc}
   */
  protected function getGroupedIdsMap(): array {
    return $this->groupedIdsMap;
  }

}
