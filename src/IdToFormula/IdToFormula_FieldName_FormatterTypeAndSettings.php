<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Label\Formula_Label;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Donquixote\ObCK\Text\Text;
use Drupal\Core\Field\FormatterPluginManager;
use Drupal\renderkit\Helper\FieldDefinitionLookup;

class IdToFormula_FieldName_FormatterTypeAndSettings implements IdToFormulaInterface {

  /**
   * @var \Drupal\renderkit\Helper\FieldDefinitionLookupInterface
   */
  private $fieldDefinitionLookup;

  /**
   * @var string
   */
  private string $entityType;

  /**
   * @var \Drupal\Core\Field\FormatterPluginManager
   */
  private FormatterPluginManager $formatterManager;

  /**
   * @param string $entityType
   * @param \Drupal\Core\Field\FormatterPluginManager $formatterManager
   */
  public function __construct(
    string $entityType,
    FormatterPluginManager $formatterManager
  ) {
    $this->fieldDefinitionLookup = FieldDefinitionLookup::createBuffered();
    $this->entityType = $entityType;
    $this->formatterManager = $formatterManager;
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($id): ?FormulaInterface {

    $fieldDefinition = $this->fieldDefinitionLookup->etAndFieldNameGetDefinition(
      $this->entityType,
      $id);

    if (NULL === $fieldDefinition) {
      return NULL;
    }

    $formula = IdToFormula_FormatterTypeName_FormatterSettings::createDrilldownFormula(
      $this->formatterManager,
      $fieldDefinition);

    return new Formula_Label($formula, Text::t('Formatter'));
  }
}
