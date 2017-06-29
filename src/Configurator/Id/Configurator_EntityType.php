<?php

namespace Drupal\renderkit\Configurator\Id;

use Drupal\cfrapi\Configurator\Id\Configurator_SelectBase;

class Configurator_EntityType extends Configurator_SelectBase {

  /**
   * @return string[]|string[][]|mixed[]
   */
  protected function getSelectOptions() {

    $options = [];
    foreach (entity_get_info() as $entityType => $entityTypeInfo) {
      $options[$entityType] = $entityTypeInfo['label'];
    }

    return $options;
  }

  /**
   * @param string $id
   *
   * @return string|null
   */
  protected function idGetLabel($id) {

    if (NULL === $info = entity_get_info($id)) {
      return NULL;
    }

    return $info['label'];
  }

  /**
   * @param string $id
   *
   * @return bool
   */
  protected function idIsKnown($id) {
    return NULL !== entity_get_info($id);
  }
}
