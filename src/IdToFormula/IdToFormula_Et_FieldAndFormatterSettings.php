<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownVal;
use Donquixote\ObCK\Formula\Label\Formula_Label;
use Drupal\renderkit\Formula\Formula_EntityType_WithFields;
use Drupal\renderkit\Formula\Formula_FieldNameWithFormatter_SpecificEt;

class IdToFormula_Et_FieldAndFormatterSettings implements IdToFormulaInterface {

  /**
   * @return \Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownValInterface
   */
  public static function createDrilldownValFormula() {

    return Formula_DrilldownVal::createArrify(
      self::createDrilldownFormula());
  }

  /**
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function createDrilldownFormula() {

    $etSelector = new Formula_EntityType_WithFields(
      \Drupal::service('entity_field.manager'),
      \Drupal::service('entity_type.repository'));

    return Formula_Drilldown::create(
      $etSelector,
      new self())
      ->withKeys('entity_type', 'field_and_formatter');
  }

  /**
   * @param string $entityType
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($entityType): ?FormulaInterface {

    $formula = Formula_FieldNameWithFormatter_SpecificEt::create($entityType);

    return new Formula_Label($formula, t('Field'));
  }
}
