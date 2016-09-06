<?php

namespace Drupal\renderkit\EnumMap;

use Drupal\cfrapi\EnumMap\EnumMapInterface;

class EnumMap_EntityViewMode implements EnumMapInterface {

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
    $options = [];
    if (!empty($entity_info['view modes'])) {
      foreach ($entity_info['view modes'] as $mode => $settings) {
        $options[$mode] = $settings['label'];
      }
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
    if (isset($entity_info['view modes'][$id]['label'])) {
      return $entity_info['view modes'][$id]['label'];
    }
    elseif (isset($entity_info['view modes'][$id])) {
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
    return isset($entity_info['view modes'][$id]);
  }
}
