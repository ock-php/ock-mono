<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityDisplay\Decorator;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit8\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit8\EntityToEntity\EntityToEntityInterface;

class EntityDisplay_RelatedEntity implements EntityDisplayInterface {

  /**
   * @var \Drupal\renderkit8\EntityToEntity\EntityToEntityInterface
   */
  private $entityToEntity;

  /**
   * @var \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface
   */
  private $relatedEntityDisplay;

  /**
   * @CfrPlugin(
   *   id = "related",
   *   label = "Related entity"
   * )
   *
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function createSchema(CfContextInterface $context = NULL) {

    return CfSchema_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        new CfSchema_IfaceWithContext(EntityToEntityInterface::class, $context),
        new CfSchema_IfaceWithContext(EntityDisplayInterface::class),
      ],
      [
        t('Entity relation'),
        t('Related entity display'),
      ]);
  }

  /**
   * @param \Drupal\renderkit8\EntityToEntity\EntityToEntityInterface $entityToEntity
   * @param \Drupal\renderkit8\EntityDisplay\EntityDisplayInterface $relatedEntityDisplay
   */
  public function __construct(EntityToEntityInterface $entityToEntity, EntityDisplayInterface $relatedEntityDisplay) {
    $this->entityToEntity = $entityToEntity;
    $this->relatedEntityDisplay = $relatedEntityDisplay;
  }

  /**
   * Builds render arrays from the entities provided.
   *
   * Both the entities and the resulting render arrays are in plural, to allow
   * for more performant implementations.
   *
   * Array keys and their order must be preserved, although implementations
   * might remove some keys that are empty.
   *
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *   Entity objects for which to build the render arrays.
   *   The array keys can be anything, they don't need to be the entity ids.
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

    return $this->relatedEntityDisplay->buildEntities($relatedEntities);
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   */
  public function buildEntity(EntityInterface $entity) {
    if (NULL === $relatedEntity = $this->entityToEntity->entityGetRelated($entity)) {
      return [];
    }
    return $this->relatedEntityDisplay->buildEntity($relatedEntity);
  }
}
