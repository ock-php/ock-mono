<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityFilter;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\renderkit\EntityToRelatedIds\EntityToRelatedIdsInterface;

class EntityFilter_RelatedEntity implements EntityFilterInterface {

  /**
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  private EntityStorageInterface $targetEntitiesStorage;

  /**
   * Constructor.
   *
   * @param \Drupal\renderkit\EntityToRelatedIds\EntityToRelatedIdsInterface $relation
   * @param \Drupal\renderkit\EntityFilter\EntityFilterInterface $filter
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   *
   * @todo Add a formula, with context conversion for referenced entity type.
   */
  public function __construct(
    private readonly EntityToRelatedIdsInterface $relation,
    private readonly EntityFilterInterface $filter,
    EntityTypeManagerInterface $entityTypeManager,
  ) {
    $this->targetEntitiesStorage = $entityTypeManager
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
        }
      }
    }

    return $entitiesHaveQuality;
  }
}
