<?php
declare(strict_types=1);

namespace Drupal\renderkit\Context;

use Donquixote\ObCK\Context\CfContext;
use Drupal\renderkit\Util\UtilBase;

final class FieldContext extends UtilBase {

  /**
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\ObCK\Context\CfContext|null
   */
  public static function get(array $allowedFieldTypes = NULL, $entityType = NULL, $bundle = NULL): ?CfContext {

    if ([] === $values = self::getValues(
      $allowedFieldTypes,
        $entityType,
        $bundle)
    ) {
      return NULL;
    }

    return new CfContext($values);
  }

  /**
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return array
   */
  public static function getValues(array $allowedFieldTypes = NULL, $entityType = NULL, $bundle = NULL): array {

    $values = EntityContext::getValues($entityType, $bundle);

    if (NULL !== $allowedFieldTypes) {
      $values ['allowedFieldTypes'] = $allowedFieldTypes;
    }

    return $values;
  }

}
