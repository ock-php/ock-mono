<?php

namespace Drupal\renderkit\EntityToEntity;

use Drupal\cfrapi\Configurator\Id\Configurator_LegendSelect;
use Drupal\cfrreflection\Configurator\Configurator_CallbackConfigurable;
use Drupal\renderkit\EnumMap\EnumMap_FieldName;

class EntityToEntity_EntityReferenceField extends EntityToEntityBase {

  /**
   * @var string
   */
  private $fieldName;

  /**
   * @var array
   */
  private $fieldInfo;

  /**
   * @CfrPlugin(
   *   id = "entityReferenceField",
   *   label = "Entity reference field"
   * )
   *
   * @param string $entityType
   *   (optional) Contextual parameter.
   * @param string $bundleName
   *   (optional) Contextual parameter.
   *
   * @return \Drupal\cfrapi\Configurator\ConfiguratorInterface
   */
  public static function createConfigurator($entityType = NULL, $bundleName = NULL) {
    $legend = new EnumMap_FieldName(array('entityreference'), $entityType, $bundleName);
    $configurators = array(Configurator_LegendSelect::createRequired($legend));
    $labels = array(t('Entity reference field'));
    return Configurator_CallbackConfigurable::createFromClassName(__CLASS__, $configurators, $labels);
  }

  /**
   * @param string $fieldName
   *
   * @return self|null
   */
  public static function create($fieldName) {
    $fieldInfo = field_info_field($fieldName);
    if (NULL === $fieldInfo) {
      return NULL;
    }
    return new self($fieldName, $fieldInfo);
  }

  /**
   * @param string $fieldName
   * @param array $fieldInfo
   */
  public function __construct($fieldName, array $fieldInfo) {
    dpm($fieldInfo, __METHOD__);
    $this->fieldName = $fieldName;
    $this->fieldInfo = $fieldInfo;
  }

  /**
   * Gets the entity type of the referenced entities.
   *
   * @return string
   */
  function getTargetType() {
    // @todo Maybe this is in a sub-array?
    return $this->fieldInfo['target_type'];
  }

  /**
   * @param string $entityType
   * @param object $entity
   *
   * @return object|null
   */
  function entityGetRelated($entityType, $entity) {
    $items = field_get_items($entityType, $entity, $this->fieldName) ?: NULL;
    if (NULL === $items) {
      return NULL;
    }
    $item = reset($items);
  }
}
