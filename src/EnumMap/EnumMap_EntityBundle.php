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
  public function __construct($entityType) {
    $this->entityType = $entityType;
  }

  /**
   * @return mixed[]
   */
  public function getSelectOptions() {
    $entity_info = entity_get_info($this->entityType);
    if (empty($entity_info['bundles'])) {
      return [];
    }
    $options = [];
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
  public function idGetLabel($id) {
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
  public function idIsKnown($id) {
    $entity_info = entity_get_info($this->entityType);
    return isset($entity_info['bundles'][$id]);
  }
}
