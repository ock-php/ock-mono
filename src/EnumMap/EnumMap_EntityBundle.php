<?php

namespace Drupal\renderkit\EnumMap;

use Drupal\cfrapi\EnumMap\EnumMapInterface;

class EnumMap_EntityBundle implements EnumMapInterface {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @param string $entityType
   */
  function __construct($entityType) {
    $this->entityType = $entityType;
  }

  /**
   * @return mixed[]
   */
  function getSelectOptions() {
    $entity_info = entity_get_info($this->entityType);
    if (empty($entity_info['bundles'])) {
      return array();
    }
    $options = array();
    foreach ($entity_info['bundles'] as $bundle => $bundle_info) {
      $options[$bundle] = $bundle_info['label'];
    }
    return $options;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  function idGetLabel($id) {
    $entity_info = entity_get_info($this->entityType);
    if (isset($entity_info['bundles'][$id]['label'])) {
      return $entity_info['bundles'][$id]['label'];
    }
    elseif (isset($entity_info['bundles'][$id])) {
      return $id;
    }
    else {
      return NULL;
    }
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  function idIsKnown($id) {
    $entity_info = entity_get_info($this->entityType);
    return isset($entity_info['bundles'][$id]);
  }
}
