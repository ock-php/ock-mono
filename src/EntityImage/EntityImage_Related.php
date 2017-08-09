<?php

namespace Drupal\renderkit8\EntityImage;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\EntityToEntity\EntityToEntityInterface;

class EntityImage_Related implements EntityImageInterface {

  /**
   * @var \Drupal\renderkit8\EntityToEntity\EntityToEntityInterface
   */
  private $entityToEntity;

  /**
   * @var \Drupal\renderkit8\EntityImage\EntityImageInterface
   */
  private $relatedEntityImage;

  /**
   * @var string
   */
  private $relatedEntityType;

  /**
   * @CfrPlugin(
   *   id = "related",
   *   label = "Image from related entity"
   * )
   *
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function createSchema(CfContextInterface $context = NULL) {

    return CfSchema_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        new CfSchema_IfaceWithContext(EntityToEntityInterface::class, $context),
        // This one is without context, because we no longer know the entity type.
        new CfSchema_IfaceWithContext(EntityImageInterface::class),
      ],
      [
        t('Entity relation'),
        t('Related entity image provider'),
      ]);
  }

  /**
   * @param \Drupal\renderkit8\EntityToEntity\EntityToEntityInterface $entityToEntity
   * @param \Drupal\renderkit8\EntityImage\EntityImageInterface $relatedEntityImage
   */
  public function __construct(EntityToEntityInterface $entityToEntity, EntityImageInterface $relatedEntityImage) {
    $this->entityToEntity = $entityToEntity;
    $this->relatedEntityImage = $relatedEntityImage;
    $this->relatedEntityType = $entityToEntity->getTargetType();
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   Single entity object for which to build a render arary.
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {
    if (NULL === $relatedEntity = $this->entityToEntity->entityGetRelated($entity_type, $entity)) {
      return [];
    }
    return $this->relatedEntityImage->buildEntity($relatedEntity);
  }

  /**
   * Same method signature as in parent interface, just a different description.
   *
   * @param string $entityType
   *   E.g. 'node' or 'taxonomy_term'.
   * @param object[] $entities
   *   Entity objects for which to build the render arrays.
   *
   * @return array[]
   *   An array of render arrays, keyed by the original array keys of $entities.
   *   Each render array must contain '#theme' => 'image'.
   */
  public function buildEntities($entityType, array $entities) {
    $entities = $this->entityToEntity->entitiesGetRelated($entityType, $entities);
    return $this->relatedEntityImage->buildEntities($this->relatedEntityType, $entities);
  }
}
