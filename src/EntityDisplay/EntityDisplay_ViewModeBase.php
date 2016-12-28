<?php

namespace Drupal\renderkit\EntityDisplay;

use Drupal\renderkit\Util\EntityUtil;

abstract class EntityDisplay_ViewModeBase extends EntitiesDisplayBase {

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *
   * @see entity_view()
   * @see node_view_multiple()
   */
  public function buildEntities($entityType, array $entities) {

    if (empty($entities)) {
      // entity_view() does not like an empty array of entities.
      // Especially, node_view_multiple() really does not.
      return [];
    }

    if (NULL === $viewMode = $this->etGetViewMode($entityType)) {
      return [];
    }

    /** @var array|false $builds_by_type */
    $builds_by_type = entity_view($entityType, $entities, $viewMode);
    if ($builds_by_type === FALSE) {
      return [];
    }
    $builds_by_etid = $builds_by_type[$entityType];
    $builds_by_delta = [];

    foreach (EntityUtil::entitiesGetIds($entityType, $entities) as $delta => $etid) {
      if (isset($builds_by_etid[$etid])) {
        $builds_by_delta[$delta] = $builds_by_etid[$etid];
      }
    }

    return $builds_by_delta;
  }

  /**
   * @param string $entityType
   *
   * @return string|null
   */
  abstract protected function etGetViewMode($entityType);
}
