<?php

namespace Drupal\renderkit\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

/**
 * Schema for a view mode machine name that is used across entity types.
 *
 * This is currently not used anywhere, but may be used by whoever finds it
 * useful.
 */
class CfSchema_EntityGenericViewModeName implements CfSchema_OptionsInterface {

  /**
   * @return string[][]
   */
  public function getGroupedOptions() {

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

    return ['' => $options];
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  public function idGetLabel($id) {

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
  public function idIsKnown($id) {

    foreach (entity_get_info() as $type_entity_info) {
      if (isset($type_entity_info['view modes'][$id])) {
        return TRUE;
      }
    }

    return FALSE;
  }
}
