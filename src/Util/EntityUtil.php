<?php

namespace Drupal\renderkit\Util;

final class EntityUtil extends UtilBase {

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return int[]
   */
  static function entitiesGetIds($entityType, $entities) {

    $info = entity_get_info($entityType);
    $idKey = $info['entity keys']['id'];

    $idsByDelta = array();
    foreach ($entities as $delta => $entity) {
      if (isset($entity->$idKey)) {
        $idsByDelta[$delta] = $entity->$idKey;
      }
    }
    return $idsByDelta;
  }

}
