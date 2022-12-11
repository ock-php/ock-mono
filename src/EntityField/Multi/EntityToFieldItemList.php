<?php
declare(strict_types=1);

namespace Drupal\renderkit\EntityField\Multi;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Formula;
use Drupal\renderkit\Context\FieldContext;
use Drupal\renderkit\Util\UtilBase;

final class EntityToFieldItemList extends UtilBase {

  /**
   * @param string[]|null $allowedFieldTypes
   * @param string|null $entityType
   * @param string|null $bundle
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function formula(
    array $allowedFieldTypes = NULL,
    $entityType = NULL,
    $bundle = NULL
  ): FormulaInterface {
    return Formula::iface(
      EntityToFieldItemListInterface::class,
      FieldContext::get(
        $allowedFieldTypes,
        $entityType,
        $bundle,
      ),
    );
  }

}
