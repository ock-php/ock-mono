<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToEntity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\EntityReferenceItem;
use Drupal\Core\TypedData\Exception\MissingDataException;
use Drupal\user\UserInterface;
use Ock\Ock\Attribute\Plugin\OckPluginInstance;

#[OckPluginInstance('author', 'Entity author')]
class EntityToEntity_EntityAuthor implements EntityToEntityInterface {

  /**
   * @return \Drupal\renderkit\EntityToEntity\EntityToEntityInterface
   */
  #[OckPluginInstance('userEntityOrAuthor', 'User entity or author')]
  public static function userEntityOrAuthor(): EntityToEntityInterface {
    return new EntityToEntity_SelfOrOther(new self());
  }

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType(): string {
    return 'user';
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return null|\Drupal\Core\Entity\EntityInterface
   */
  public function entityGetRelated(EntityInterface $entity): ?EntityInterface {

    if (!$entity instanceof FieldableEntityInterface) {
      return NULL;
    }

    // @todo Maybe some entity types use a different field name for author?
    try {
      $item = $entity->get('uid')->first();
    }
    catch (MissingDataException $e) {
      // No need to log this. It just means the list is empty.
      unset($e);
      return NULL;
    }

    if (!$item instanceof EntityReferenceItem) {
      return NULL;
    }

    $user = $item->__get('entity');

    if (!$user instanceof UserInterface) {
      return NULL;
    }

    return $user;
  }

}
