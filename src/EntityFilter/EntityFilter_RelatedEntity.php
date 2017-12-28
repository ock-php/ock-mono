<?php
declare(strict_types=1);

namespace Drupal\renderkit8\EntityFilter;

use Drupal\renderkit8\EntityToRelatedIds\EntityToRelatedIdsInterface;

class EntityFilter_RelatedEntity implements EntityFilterInterface {

  /**
   * @var \Drupal\renderkit8\EntityToRelatedIds\EntityToRelatedIdsInterface
   */
  private $relation;

  /**
   * @var \Drupal\renderkit8\EntityFilter\EntityFilterInterface
   */
  private $filter;

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private $targetEntitiesStorage;

  /**
   * @param \Drupal\renderkit8\EntityToRelatedIds\EntityToRelatedIdsInterface $relation
   * @param \Drupal\renderkit8\EntityFilter\EntityFilterInterface $filter
   */
  public function __construct(EntityToRelatedIdsInterface $relation, EntityFilterInterface $filter) {
    $this->filter = $filter;
    $this->relation = $relation;
    $this->targetEntitiesStorage = \Drupal::service('entity_type.manager')
      ->getStorage($relation->getTargetType());
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return string[]|int[]
   *   Format: $[] = $delta
   */
  public function entitiesFilterDeltas(array $entities): array {

    // The IDE has problems to determine the type, although all information is there.
    /** @var string[][] $relatedIdsByDelta */
    $relatedIdsByDelta = $this->relation->entitiesGetRelatedIds($entities);

    $relatedIds = [];
    foreach ($relatedIdsByDelta as $delta => $deltaRelatedIds) {
      foreach ($deltaRelatedIds as $relatedId) {
        $relatedIds[] = $relatedId;
      }
    }

    $relatedIds = array_unique($relatedIds);
    $relatedEntitiesById = $this->targetEntitiesStorage->loadMultiple($relatedIds);

    $relatedEntitiesHaveQuality = $this->filter->entitiesFilterDeltas(
      $relatedEntitiesById);

    $entitiesHaveQuality = [];
    foreach ($relatedIdsByDelta as $delta => $deltaRelatedIds) {
      foreach ($deltaRelatedIds as $id => $relatedEntity) {
        if (!empty($relatedEntitiesHaveQuality[$id])) {
          $entitiesHaveQuality[$delta] = TRUE;
          continue;
        }
      }
    }

    return $entitiesHaveQuality;
  }
}
