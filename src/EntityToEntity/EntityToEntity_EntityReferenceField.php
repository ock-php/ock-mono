<?php

namespace Drupal\renderkit8\EntityToEntity;

use Donquixote\Cf\Schema\GroupVal\CfSchema_GroupVal_Callback;
use Drupal\renderkit8\Schema\CfSchema_FieldName;

class EntityToEntity_EntityReferenceField extends EntityToEntityMultipleBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var string
   */
  private $targetType;

  /**
   * @CfrPlugin("entityReferenceField", "Entity reference field")
   *
   * @param string $entityType
   *   (optional) Contextual parameter.
   * @param string $bundleName
   *   (optional) Contextual parameter.
   *
   * @return \Donquixote\Cf\Schema\GroupVal\CfSchema_GroupValInterface
   */
  public static function createSchema($entityType = NULL, $bundleName = NULL) {

    return CfSchema_GroupVal_Callback::fromStaticMethod(
      __CLASS__,
      'create',
      [
        new CfSchema_FieldName(
          ['entityreference'],
          $entityType,
          $bundleName)
      ],
      [
        t('Entity reference field'),
      ]);
  }

  /**
   * @param string $fieldName
   *
   * @return self|null
   */
  public static function create($fieldName) {
    $fieldInfo = field_info_field($fieldName);
    if (NULL === $fieldInfo) {
      throw new \InvalidArgumentException("Field '$fieldName' does not exist.");
    }
    if (!isset($fieldInfo['type'])) {
      throw new \InvalidArgumentException("Field '$fieldName' has no field type.");
    }
    if ($fieldInfo['type'] !== 'entityreference') {
      $typeExport = var_export($fieldInfo['type'], TRUE);
      throw new \InvalidArgumentException("Field type of '$fieldName' expected to be 'entityreference', $typeExport found instead.");
    }
    if (!isset($fieldInfo['settings']['target_type'])) {
      throw new \InvalidArgumentException("No target type in field info.");
    }
    return new self($fieldName, $fieldInfo['settings']['target_type']);
  }

  /**
   * @param string $fieldName
   * @param string $targetType
   */
  public function __construct($fieldName, $targetType) {
    $this->fieldName = $fieldName;
    $this->targetType = $targetType;
  }

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  public function getTargetType() {
    return $this->targetType;
  }

  /**
   * @param \Drupal\Core\Entity\EntityInterface[] $entities
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  public function entitiesGetRelated(array $entities) {

    $target_etids = [];
    foreach ($entities as $delta => $entity) {
      $items = field_get_items($entityType, $entity, $this->fieldName) ?: NULL;
      if (NULL === $items) {
        continue;
      }
      $item = reset($items);
      if (empty($item['target_id'])) {
        continue;
      }
      $target_etids[$delta] = $item['target_id'];
    }

    $target_entities_by_etid = entity_load($this->targetType, $target_etids);

    $target_entities_by_delta = [];
    foreach ($target_etids as $delta => $target_etid) {
      if (array_key_exists($target_etid, $target_entities_by_etid)) {
        $target_entities_by_delta[$delta] = $target_entities_by_etid[$target_etid];
      }
    }

    return $target_entities_by_delta;
  }
}
