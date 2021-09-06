<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownVal;
use Donquixote\ObCK\Formula\Group\Formula_Group;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_FixedValue;
use Drupal\renderkit\IdToFormula\IdToFormula_Et_FieldName;
use Drupal\renderkit\Util\UtilBase;

/**
 * Formula where the value is like ['entity_type' => 'node', 'field_name' => 'body'].
 */
final class Formula_EtAndFieldName extends UtilBase {

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function create(
    array $allowedFieldTypes = NULL,
    $entityType = NULL,
    $bundleName = NULL
  ) {

    // @todo Real dependency injection.

    if (NULL !== $entityType) {
      return new Formula_Group(
        [
          // @todo This should be _FixedConf instead.
          'entity_type' => new Formula_ValueProvider_FixedValue($entityType),
          'field_name' => new Formula_FieldName_AllowedTypes(
            $entityType,
            $bundleName,
            $allowedFieldTypes),
        ],
        [
          'entity_type' => t('Entity type'),
          'field_name' => t('Field name'),
        ]);
    }

    return Formula_DrilldownVal::createArrify(
      Formula_Drilldown::create(
        Formula_EntityType::createOptionsFormula(),
        new IdToFormula_Et_FieldName(
          $allowedFieldTypes,
          $bundleName))
        ->withKeys('entity_type', 'field_name'));

  }
}
