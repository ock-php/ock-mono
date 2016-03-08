<?php

namespace Drupal\renderkit\EntityToEntity;

/**
 * @CfrPlugin(
 *   id = "author",
 *   label = "Entity author"
 * )
 */
class EntityToEntity_EntityAuthor implements EntityToEntityInterface {

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  function getTargetType() {
    return 'user';
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[]
   */
  function entitiesGetRelated($entityType, array $entities) {
    // @todo Check if this entity type has a uid!
    $uids = array();
    foreach ($entities as $delta => $entity) {
      if (!isset($entity->uid)) {
        continue;
      }
      $uid = $entity->uid;
      if ((string)(int)$uid !== (string)$uid || $uid <= 0) {
        continue;
      }
      $uids[$delta] = $entity->uid;
    }
    $usersByUid = user_load_multiple($uids);
    $usersByDelta = array();
    foreach ($uids as $delta => $uid) {
      if (array_key_exists($uid, $usersByUid)) {
        $usersByDelta[$delta] = $usersByUid[$uid];
      }
    }
    return $usersByDelta;
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object|null
   */
  function entityGetRelated($entityType, $entity) {
    // @todo Check if this entity type has a uid!
    if (!isset($entity->uid)) {
      return NULL;
    }
    $uid = $entity->uid;
    if ((string)(int)$uid !== (string)$uid || $uid <= 0) {
      return NULL;
    }
    return user_load($uid);
  }
}
