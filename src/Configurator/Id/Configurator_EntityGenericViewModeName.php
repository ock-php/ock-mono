<?php

namespace Drupal\renderkit\Configurator\Id;

use Drupal\cfrapi\Configurator\Id\Configurator_SelectBase;

/**
 * Configurator for a view mode machine name that is used across entity types.
 *
 * This is currently not used anywhere, but may be used by whoever finds it
 * useful.
 */
class Configurator_EntityGenericViewModeName extends Configurator_SelectBase {

  /**
   * @param bool $required
   */
  public function __construct($required = TRUE) {
    parent::__construct($required);
  }

  /**
   * @return mixed[]
   */
  protected function getSelectOptions() {

    $modes = [];
    foreach (entity_get_info() as $type => $type_entity_info) {

      if (empty($type_entity_info['view modes'])) {
        continue;
      }

      foreach ($type_entity_info['view modes'] as $mode => $settings) {
        $modes[$mode][] = isset($settings['label'])
          ? $settings['label']
          : $mode;
      }
    }

    $options = [];
    foreach ($modes as $mode => $aliases) {
      $options[$mode] = implode(' / ', array_unique($aliases));
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  protected function idGetLabel($id) {

    $aliases = [];
    foreach (entity_get_info() as $type_entity_info) {
      if (isset($type_entity_info['view modes'][$id]['label'])) {
        $aliases[] = $type_entity_info['view modes'][$id]['label'];
      }
      elseif (isset($type_entity_info['view modes'][$id])) {
        $aliases[] = $id;
      }
    }

    if ([] === $aliases) {
      return NULL;
    }

    return implode(' / ', array_unique($aliases));
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  protected function idIsKnown($id) {

    foreach (entity_get_info() as $type_entity_info) {
      if (isset($type_entity_info['view modes'][$id])) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
