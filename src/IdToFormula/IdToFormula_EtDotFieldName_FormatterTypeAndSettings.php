<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Drupal\renderkit\Helper\FieldDefinitionLookup;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;

class IdToFormula_EtDotFieldName_FormatterTypeAndSettings implements IdToFormulaInterface {

  /**
   * @var \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  private $fieldDefinitionLookup;

  /**
   * @param string|null $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   */
  public static function createDrilldownFormula($entityType = NULL, $bundleName = NULL): Formula_DrilldownInterface {

    return Formula_Drilldown::create(
      Formula_EtDotFieldName::create(
        NULL,
        $entityType,
        $bundleName),
      new self($entityType, $bundleName))
      ->withKeys('field', 'display');
  }

  /**
   * @param string $entityType
   * @param string $bundleName
   */
  public function __construct($entityType = NULL, $bundleName = NULL) {

    $this->fieldDefinitionLookup = FieldDefinitionLookup::createBuffered();
  }

  /**
   * @param string|int $etAndFieldName
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($etAndFieldName): ?FormulaInterface {
    list($et, $fieldName) = explode('.', $etAndFieldName . '.');

    $fieldDefinition = $this->fieldDefinitionLookup->etAndFieldNameGetDefinition(
      $et,
      $fieldName);

    if (NULL === $fieldDefinition) {
      return NULL;
    }

    return IdToFormula_FormatterTypeName_FormatterSettings::createDrilldownFormula(
      \Drupal::service('plugin.manager.field.formatter'),
      $fieldDefinition);
  }

}
