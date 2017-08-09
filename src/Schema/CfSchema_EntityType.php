<?php

namespace Drupal\renderkit8\Schema;

use Donquixote\Cf\Schema\Options\CfSchema_OptionsInterface;

class CfSchema_EntityType implements CfSchema_OptionsInterface {

  /**
   * @return string[][]
   */
  public function getGroupedOptions() {

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
  public function idGetLabel($id) {

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
  public function idIsKnown($id) {
    return NULL !== entity_get_info($id);
  }
}
