<?php

namespace Drupal\renderkit\EntityToEntity;

/**
 * @CfrPlugin(
 *   id = "author",
 *   label = @t("Entity author")
 * )
 */
class EntityToEntity_EntityAuthor implements EntityToEntityInterface {

  /**
   * @CfrPlugin(
   *   id = "userEntityOrAuthor",
   *   label = @t("User entity or author")
   * )
   *
   * @return \Drupal\renderkit\EntityToEntity\EntityToEntityInterface
   */
  public static function userEntityOrAuthor() {
    return new EntityToEntity_SelfOrOther(new self());
  }

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType() {
    return 'user';
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return object[]
   */
  public function entitiesGetRelated($entityType, array $entities) {
    // @todo Check if this entity type has a uid!
    $uids = [];
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
    $usersByDelta = [];
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
  public function entityGetRelated($entityType, $entity) {
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
