<?php

namespace Drupal\renderkit8\EntityField\Single;

use Donquixote\Cf\Schema\CfSchema;
use Drupal\renderkit8\Context\FieldContext;
use Drupal\renderkit8\Util\UtilBase;

final class EntityToFieldItem extends UtilBase {

  /**
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Cf\Schema\CfSchemaInterface
   */
  public static function schema(
    array $allowedFieldTypes = NULL,
    $entityType = NULL,
    $bundle = NULL
  ) {
    return CfSchema::iface(
      EntityToFieldItemInterface::class,
      FieldContext::get(
        $allowedFieldTypes,
        $entityType,
        $bundle));
  }

}
