<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Formula\Group\Formula_Group;
use Donquixote\ObCK\Formula\Label\Formula_Label;
use Donquixote\ObCK\Formula\ValueProvider\Formula_ValueProvider_FixedValue;
use Drupal\renderkit\IdToFormula\IdToFormula_Et_FieldAndFormatterSettings;
use Drupal\renderkit\Util\UtilBase;

final class Formula_EtAndFieldNameAndFormatterSettings extends UtilBase {

  /**
   * @param null $entityType
   * @param null $bundleName
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function create($entityType = NULL, $bundleName = NULL) {

    if (NULL === $entityType) {
      $formula = IdToFormula_Et_FieldAndFormatterSettings::createDrilldownValFormula();

      return new Formula_Label($formula, t('Entity type'));
    }

    return new Formula_Group(
      [
        'entity_type' => new Formula_ValueProvider_FixedValue($entityType),
        'field_and_formatter' => Formula_FieldNameWithFormatter_SpecificEt::create(
          $entityType,
          $bundleName),
      ],
      [
        t('Entity type'),
        t('Field and formatter'),
      ]);
  }

}
