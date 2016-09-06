<?php

namespace Drupal\renderkit\EntityFilter;

use Drupal\renderkit\EntityToRelatedIds\EntityToRelatedIdsInterface;

class EntityFilter_RelatedEntity implements EntityFilterInterface {

  /**
   * @var \Drupal\renderkit\EntityToRelatedIds\EntityToRelatedIdsInterface
   */
  private $relation;

  /**
   * @var \Drupal\renderkit\EntityFilter\EntityFilterInterface
   */
  private $filter;

  /**
   * @param \Drupal\renderkit\EntityToRelatedIds\EntityToRelatedIdsInterface $relation
   * @param \Drupal\renderkit\EntityFilter\EntityFilterInterface $filter
   */
  public function __construct(EntityToRelatedIdsInterface $relation, EntityFilterInterface $filter) {
    $this->filter = $filter;
    $this->relation = $relation;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return bool[]
   */
  public function entitiesFilterDeltas($entityType, array $entities) {
    $relatedIdsByDelta = $this->relation->entitiesGetRelatedIds($entityType, $entities);
    $relatedIds = [];
    foreach ($relatedIdsByDelta as $delta => $deltaRelatedIds) {
      foreach ($deltaRelatedIds as $relatedId) {
        $relatedIds[] = $relatedId;
      }
    }
    $relatedIds = array_unique($relatedIds);
    $relatedEntitiesById = entity_load($this->relation->getTargetType(), $relatedIds);

    $relatedEntitiesHaveQuality = $this->filter->entitiesFilterDeltas($this->relation->getTargetType(), $relatedEntitiesById);
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
