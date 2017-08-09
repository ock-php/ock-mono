<?php

namespace Drupal\renderkit8\Util;

final class EntityUtil extends UtilBase {

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return int[]
   */
  public static function entitiesGetIds($entityType, $entities) {

    $info = entity_get_info($entityType);
    $idKey = $info['entity keys']['id'];

    $idsByDelta = [];
    foreach ($entities as $delta => $entity) {
      if (isset($entity->$idKey)) {
        $idsByDelta[$delta] = $entity->$idKey;
      }
    }
    return $idsByDelta;
  }

}
