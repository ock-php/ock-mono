<?php
declare(strict_types=1);

namespace Drupal\renderkit8\Context;

use Donquixote\Cf\Context\CfContext;
use Drupal\renderkit8\Util\UtilBase;

final class FieldContext extends UtilBase {

  /**
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\Context\CfContext|null
   */
  public static function get(array $allowedFieldTypes = NULL, $entityType = NULL, $bundle = NULL) {

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
  public static function getValues(array $allowedFieldTypes = NULL, $entityType = NULL, $bundle = NULL) {

    $values = EntityContext::getValues($entityType, $bundle);

    if (NULL !== $allowedFieldTypes) {
      $values ['allowedFieldTypes'] = $allowedFieldTypes;
    }

    return $values;
  }

}
