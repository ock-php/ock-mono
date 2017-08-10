<?php

namespace Drupal\renderkit8\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;

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
   * @return \Drupal\renderkit8\EntityToEntity\EntityToEntityInterface
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
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entitiesGetRelated(array $entities) {
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
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity) {
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
