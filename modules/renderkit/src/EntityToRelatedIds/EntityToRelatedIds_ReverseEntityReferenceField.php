<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityToRelatedIds;

use Drupal\Core\Database\Database;
use Drupal\renderkit\Util\EntityUtil;

class EntityToRelatedIds_ReverseEntityReferenceField extends EntityToRelatedIds_MultipleBase {

  /**
   * @param string $fieldSourceType
   * @param string $fieldName
   * @param string $fieldTargetType
   */
  public function __construct(
    private readonly string $fieldSourceType,
    private readonly string $fieldName,
    private readonly string $fieldTargetType,
  ) {}

  /**
   * @return string
   */
  public function getTargetType(): string {
    return $this->fieldSourceType;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  public function entitiesGetRelatedIds(array $entities): array {

    if ([] === $entities) {
      return [];
    }

    $entities = EntityUtil::entitiesFilterByType($entities, $this->fieldTargetType);

    if ([] === $entities) {
      return [];
    }

    $idsByDelta = EntityUtil::entitiesGetIds($entities);

    $targetIdColName = $this->fieldName . '_target_id';
    // @todo Inject the database.
    // @todo This does probably not work in Drupal 8.
    $q = Database::getConnection()->select('field_data_' . $this->fieldName, 'f');
    $q->condition('entity_type', $this->fieldSourceType);
    $q->condition($targetIdColName, array_values($idsByDelta));
    $q->addField('f', $targetIdColName, 'target_id');
    $q->addField('f', 'entity_id');
    $relatedIdsById = [];
    foreach ($q->execute() ?: [] as $row) {
      $relatedIdsById[$row->target_id][] = $row->entity_id;
    }
    $relatedIdsByDelta = [];
    foreach ($idsByDelta as $delta => $etid) {
      if (array_key_exists($etid, $relatedIdsById)) {
        $relatedIdsByDelta[$delta] = $relatedIdsById[$etid];
      }
    }
    return $relatedIdsByDelta;
  }
}
