<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Formula\Label\Formula_Label;
use Drupal\renderkit\Helper\FieldDefinitionLookup;

class IdToFormula_FieldName_FormatterTypeAndSettings implements IdToFormulaInterface {

  /**
   * @var \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  private $fieldDefinitionLookup;

  /**
   * @var string
   */
  private $entityType;

  /**
   * @param string $entityType
   */
  public function __construct($entityType) {
    $this->fieldDefinitionLookup = FieldDefinitionLookup::createBuffered();
    $this->entityType = $entityType;
  }

  /**
   * @param string|int $fieldName
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($fieldName): ?FormulaInterface {

    $fieldDefinition = $this->fieldDefinitionLookup->etAndFieldNameGetDefinition(
      $this->entityType,
      $fieldName);

    if (NULL === $fieldDefinition) {
      return NULL;
    }

    $formula = IdToFormula_FormatterTypeName_FormatterSettings::createDrilldownFormula(
      \Drupal::service('plugin.manager.field.formatter'),
      $fieldDefinition);

    return new Formula_Label($formula, t('Formatter'));
  }
}
