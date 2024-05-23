<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityField\Single;

use Ock\Ock\Core\Formula\FormulaInterface;
use Ock\Ock\Formula\Formula;
use Drupal\renderkit\Context\FieldContext;
use Drupal\renderkit\Util\UtilBase;

final class EntityToFieldItem extends UtilBase {

  /**
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Ock\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(
    array $allowedFieldTypes = NULL,
    string $entityType = NULL,
    string $bundle = NULL
  ): FormulaInterface {
    return Formula::iface(
      EntityToFieldItemInterface::class,
      FieldContext::get(
        $allowedFieldTypes,
        $entityType,
        $bundle));
  }

}
