<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay\Decorator;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Formula\GroupVal\Formula_GroupVal_Callback;
use Donquixote\ObCK\Formula\Iface\Formula_IfaceWithContext;
use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityDisplay\EntityDisplayInterface;
use Drupal\renderkit\EntityToEntity\EntityToEntityInterface;

class EntityDisplay_RelatedEntity implements EntityDisplayInterface {

  /**
   * @var \Drupal\renderkit\EntityToEntity\EntityToEntityInterface
   */
  private $entityToEntity;

  /**
   * @var \Drupal\renderkit\EntityDisplay\EntityDisplayInterface
   */
  private $relatedEntityDisplay;

  /**
   * @CfrPlugin(
   *   id = "related",
   *   label = "Related entity"
   * )
   *
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\ObCK\Formula\GroupVal\Formula_GroupValInterface
   */
  public static function createFormula(CfContextInterface $context = NULL) {

    return Formula_GroupVal_Callback::fromClass(
      __CLASS__,
      [
        new Formula_IfaceWithContext(EntityToEntityInterface::class, $context),
        new Formula_IfaceWithContext(EntityDisplayInterface::class),
      ],
      [
        t('Entity relation'),
        t('Related entity display'),
      ]);
  }

  /**
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $entityToEntity
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $relatedEntityDisplay
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
