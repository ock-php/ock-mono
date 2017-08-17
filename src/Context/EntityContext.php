<?php

namespace Drupal\renderkit8\Context;

use Donquixote\Cf\Context\CfContext;
use Drupal\renderkit8\Util\UtilBase;

final class EntityContext extends UtilBase {

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\Context\CfContext|null
   */
  public static function get($entityType = NULL, $bundle = NULL) {

    if ([] === $values = self::getValues($entityType, $bundle)) {
      return NULL;
    }

    return new CfContext($values);
  }

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return array
   */
  public static function getValues($entityType = NULL, $bundle = NULL) {

    if (NULL === $entityType) {
      return [];
    }

    $values = [
      'entityType' => $entityType,
      'entity_type' => $entityType,
      'entityTypeId' => $entityType,
    ];

    if (NULL !== $bundle) {
      $values['bundle'] = $bundle;
    }

    return $values;
  }

}
