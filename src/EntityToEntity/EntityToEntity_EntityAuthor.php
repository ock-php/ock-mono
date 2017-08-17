<?php

namespace Drupal\renderkit8\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\user\UserInterface;

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
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity) {

    if (!$entity instanceof FieldableEntityInterface) {
      return NULL;
    }

    // @todo Maybe some entity types use a different field name for author?
    $item = $entity->get('uid')->first();

    if (!$item instanceof EntityReferenceItem) {
      return NULL;
    }

    $user = $item->entity;

    if (!$user instanceof UserInterface) {
      return NULL;
    }

    return $user;
  }
}
