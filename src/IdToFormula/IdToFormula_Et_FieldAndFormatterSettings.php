<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownVal;
use Donquixote\Ock\Formula\Label\Formula_Label;
use Drupal\renderkit\Formula\Formula_EntityType_WithFields;
use Drupal\renderkit\Formula\Formula_FieldNameWithFormatter_SpecificEt;

class IdToFormula_Et_FieldAndFormatterSettings implements IdToFormulaInterface {

  /**
   * @return \Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownValInterface
   */
  public static function createDrilldownValFormula(): Formula_DrilldownValInterface {

    return Formula_DrilldownVal::createArrify(
      self::createDrilldownFormula());
  }

  /**
   * @return \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function createDrilldownFormula(): Formula_DrilldownInterface {

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
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($entityType): ?FormulaInterface {

    $formula = Formula_FieldNameWithFormatter_SpecificEt::create($entityType);

    return new Formula_Label($formula, t('Field'));
  }
}
