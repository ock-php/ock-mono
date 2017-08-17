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
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return array[]
   */
  public function buildEntities(array $entities) {

    $relatedEntities = [];
    foreach ($entities as $delta => $entity) {
      if (NULL !== $related = $this->entityToEntity->entityGetRelated($entity)) {
        $relatedEntities[$delta] = $related;
      }
    }

    return $this->relatedEntityImage->buildEntities($relatedEntities);
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {
    if (NULL === $relatedEntity = $this->entityToEntity->entityGetRelated($entity)) {
      return [];
    }
    return $this->relatedEntityImage->buildEntity($relatedEntity);
  }
}
