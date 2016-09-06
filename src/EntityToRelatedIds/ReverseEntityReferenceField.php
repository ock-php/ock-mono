<?php

namespace Drupal\renderkit\EntityToRelatedIds;

use Drupal\renderkit\Util\EntityUtil;

class ReverseEntityReferenceField extends EntitiesToRelatedIdsBase {

  /**
   * @var string
   */
  private $fieldSourceType;

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var string
   */
  private $fieldTargetType;

  /**
   * @param string $fieldSourceType
   * @param string $fieldName
   * @param string $fieldTargetType
   */
  public function __construct($fieldSourceType, $fieldName, $fieldTargetType) {
    $this->fieldSourceType = $fieldSourceType;
    $this->fieldName = $fieldName;
    $this->fieldTargetType = $fieldTargetType;
  }

  /**
   * @return string
   */
  public function getTargetType() {
    return $this->fieldSourceType;
  }

  /**
   * @param string $entityType
   * @param object[] $entities
   *
   * @return int[][]
   *   Format: $[$delta][] = $relatedEntityId
   */
  public function entitiesGetRelatedIds($entityType, array $entities) {
    if ($entityType !== $this->fieldTargetType) {
      return [];
    }
    $idsByDelta = EntityUtil::entitiesGetIds($entityType, $entities);
    $targetIdColName = $this->fieldName . '_target_id';
    $q = db_select('field_data_' . $this->fieldName, 'f');
    $q->condition('entity_type', $this->fieldSourceType);
    $q->condition($targetIdColName, array_values($idsByDelta));
    $q->addField('f', $targetIdColName, 'target_id');
    $q->addField('f', 'entity_id');
    $relatedIdsById = [];
    foreach ($q->execute() as $row) {
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
