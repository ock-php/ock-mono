<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

/**
 * Schema for a string consisting of entity type plus view mode name, such
 * as 'node:teaser' or 'taxonomy_term:full'.
 */
class CfSchema_EntityTypeWithViewModeName implements CfSchema_OptionsInterface {

  /**
   * @var null|string
   */
  private $entityType;

  /**
   * @param string|null $entityType
   */
  public function __construct($entityType = NULL) {
    $this->entityType = $entityType;
  }

  /**
   * @return mixed[]
   */
  public function getGroupedOptions() {

    $options = [];
    foreach ($this->getFilteredEntityInfo() as $type => $type_entity_info) {
      if (empty($type_entity_info['view modes'])) {
        continue;
      }
      $type_label = $type_entity_info['label'];
      foreach ($type_entity_info['view modes'] as $mode => $settings) {
        $options[$type_label][$type . ':' . $mode] = $settings['label'];
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
    list($type, $mode) = explode(':', $id . ':');

    if ('' === $type || '' === $mode) {
      return NULL;
    }

    if (NULL === $type_entity_info = $this->typeGetEntityInfo($type)) {
      return NULL;
    }

    if (isset($type_entity_info['view modes'][$mode]['label'])) {
      $label = $type_entity_info['view modes'][$mode]['label'];
    }
    elseif (isset($type_entity_info['view modes'][$mode])) {
      $label = $mode;
    }
    else {
      return NULL;
    }

    return $label . '(' . $type_entity_info['label'] . ')';
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  public function idIsKnown($id) {

    list($type, $mode) = explode(':', $id . ':');

    return 1
      && '' !== $type && '' !== $mode
      && NULL !== ($type_entity_info = $this->typeGetEntityInfo($type))
      && isset($type_entity_info['view modes'][$mode]);
  }

  /**
   * @param string $type
   *
   * @return array|null
   */
  private function typeGetEntityInfo($type) {

    if (NULL !== $this->entityType && $type !== $this->entityType) {
      return NULL;
    }

    return entity_get_info($type);
  }

  /**
   * @return array[]
   *   Format: $[$entity_type] = $type_entity_info
   */
  private function getFilteredEntityInfo() {

    $entity_info = entity_get_info();

    if (NULL === $this->entityType) {
      return $entity_info;
    }

    if (!isset($entity_info[$this->entityType])) {
      return [];
    }

    return [$this->entityType => $entity_info[$this->entityType]];
  }
}
