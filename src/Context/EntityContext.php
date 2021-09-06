<?php
declare(strict_types=1);

namespace Drupal\renderkit\Context;

use Donquixote\ObCK\Context\CfContext;
use Drupal\renderkit\Util\UtilBase;

final class EntityContext extends UtilBase {

  /**
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\ObCK\Context\CfContext|null
   */
  public static function get($entityType = NULL, $bundle = NULL): ?CfContext {

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
  public static function getValues($entityType = NULL, $bundle = NULL): array {

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
