<?php

declare(strict_types=1);

namespace Donquixote\ObCK\TextLookup;

use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

/**
 * Static methods related to label providers.
 */
class TextLookup {

  /**
   * @param string $id
   * @param \Donquixote\ObCK\TextLookup\TextLookupInterface $lookup
   *
   * @return \Donquixote\ObCK\Text\TextInterface
   */
  public static function idGetLabel(string $id, TextLookupInterface $lookup): TextInterface {
    return $lookup->idsMapGetTexts([$id => TRUE])[$id]
      ?? Text::s($id);
  }

  /**
   * @param string[] $ids
   * @param \Donquixote\ObCK\TextLookup\TextLookupInterface $lookup
   *
   * @return \Donquixote\ObCK\Text\TextInterface[]
   */
  public static function idsGetLabels(array $ids, TextLookupInterface $lookup): array {
    return self::idMapGetLabels(
      array_fill_keys($ids, TRUE),
      $lookup);
  }

  /**
   * @param mixed[] $id_map
   * @param \Donquixote\ObCK\TextLookup\TextLookupInterface $lookup
   *
   * @return \Donquixote\ObCK\Text\TextInterface[]
   */
  public static function idMapGetLabels(array $id_map, TextLookupInterface $lookup): array {
    $known_labels = $lookup->idsMapGetTexts($id_map);
    $labels = [];
    foreach ($id_map as $id => $_) {
      $labels[$id] = $known_labels[$id] ?? Text::s($id);
    }
    return $labels;
  }

  /**
   * @param mixed[][] $grouped_ids_map
   *   Format: $[$group_id][$id] = $_anything.
   * @param \Donquixote\ObCK\TextLookup\TextLookupInterface $lookup
   *   Bulk label lookup.
   *
   * @return \Donquixote\ObCK\Text\TextInterface[][]
   *   Format: $[$group_id][$id] = $label.
   */
  public static function groupedIdsMapGetLabelss(array $grouped_ids_map, TextLookupInterface $lookup): array {
    $known_labels = $lookup->idsMapGetTexts(array_replace(...$grouped_ids_map));
    $labelss = [];
    foreach ($grouped_ids_map as $group_id => $ids_map) {
      foreach ($ids_map as $id => $_) {
        $labelss[$group_id][$id] = $known_labels[$group_id][$id]
          ?? Text::s($id);
      }
    }
    return $labelss;
  }

}
