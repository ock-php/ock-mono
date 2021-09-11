<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Drupal\renderkit\Formula\Formula_EtDotFieldName;
use Drupal\renderkit\Helper\FieldDefinitionLookup;

class IdToFormula_EtDotFieldName_FormatterTypeAndSettings implements IdToFormulaInterface {

  /**
   * @var \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  private $fieldDefinitionLookup;

  /**
   * @param string|null $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
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
   * @param string|int $etAndId
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($etAndId): ?FormulaInterface {
    list($et, $fieldName) = explode('.', $etAndId . '.');

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
