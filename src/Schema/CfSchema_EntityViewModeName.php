<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

/**
 * Schema for a view mode machine name for a given entity type.
 *
 * This is currently not used anywhere, but may be used by whoever finds it
 * useful.
 */
class CfSchema_EntityViewModeName implements CfSchema_OptionsInterface {

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
   * @return string[][]
   */
  public function getGroupedOptions() {

    $entity_info = entity_get_info($this->entityType);
    $options = [];
    if (!empty($entity_info['view modes'])) {
      foreach ($entity_info['view modes'] as $mode => $settings) {
        $options[$mode] = $settings['label'];
      }
    }

    return ['' => $options];
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
