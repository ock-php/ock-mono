<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityDisplay;

use Drupal\Core\Entity\EntityInterface;
use Drupal\renderkit\EntityToEntity\EntityToEntityInterface;

/**
 * Gets a referenced entity.
 *
 * @todo Register this with ock somehow.
 */
class EntityDisplay_Reference implements EntityDisplayInterface {

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\EntityDisplay\EntityDisplayInterface $decorated
   * @param \Drupal\renderkit\EntityToEntity\EntityToEntityInterface $reference
   */
  public function __construct(
    private readonly EntityDisplayInterface $decorated,
    private readonly EntityToEntityInterface $reference,
  ) {}

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
  public function buildEntities(array $entities): array {

    $relatedEntities = [];
    foreach ($entities as $delta => $entity) {
      if (NULL !== $related = $this->reference->entityGetRelated($entity)) {
        $relatedEntities[$delta] = $related;
      }
    }

    return $this->decorated->buildEntities($relatedEntities);
  }

  /**
   * Same as ->buildEntities(), just for a single entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *
   * @return array
   *
   * @see \Drupal\renderkit\EntityDisplay\EntityDisplayInterface::buildEntity()
   */
  public function buildEntity(EntityInterface $entity): array {

    if (NULL === $related = $this->reference->entityGetRelated($entity)) {
      return [];
    }

    return $this->decorated->buildEntity($related);
  }
}
