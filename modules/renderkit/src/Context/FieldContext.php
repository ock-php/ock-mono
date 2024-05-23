<?php
declare(strict_types=1);

namespace Drupal\renderkit\Context;

use Drupal\renderkit\Util\UtilBase;
use Ock\Ock\Context\CfContext;

final class FieldContext extends UtilBase {

  /**
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Ock\Ock\Context\CfContext|null
   */
  public static function get(array $allowedFieldTypes = NULL, string $entityType = NULL, string $bundle = NULL): ?CfContext {

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
  public static function getValues(array $allowedFieldTypes = NULL, string $entityType = NULL, string $bundle = NULL): array {

    $values = EntityContext::getValues($entityType, $bundle);

    if (NULL !== $allowedFieldTypes) {
      $values ['allowedFieldTypes'] = $allowedFieldTypes;
    }

    return $values;
  }

}
