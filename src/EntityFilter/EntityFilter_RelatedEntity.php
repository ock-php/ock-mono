<?php

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
   * @param \Drupal\renderkit8\EntityToRelatedIds\EntityToRelatedIdsInterface $relation
   * @param \Drupal\renderkit8\EntityFilter\EntityFilterInterface $filter
   */
  public function __construct(EntityToRelatedIdsInterface $relation, EntityFilterInterface $filter) {
    $this->filter = $filter;
    $this->relation = $relation;
  }

  /**
   * @param string $entityType
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
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
