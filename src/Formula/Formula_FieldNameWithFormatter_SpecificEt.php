<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_Drilldown;
use Donquixote\Ock\Formula\DrilldownVal\Formula_DrilldownVal;
use Donquixote\Ock\Formula\Proxy\Replacer\Formula_Proxy_ReplacerInterface;
use Donquixote\Ock\Formula\Select\Formula_SelectInterface;
use Donquixote\Ock\FormulaReplacer\FormulaReplacerInterface;
use Drupal\renderkit\IdToFormula\IdToFormula_FieldName_FormatterTypeAndSettings;

class Formula_FieldNameWithFormatter_SpecificEt implements Formula_Proxy_ReplacerInterface {

  /**
   * @var string
   */
  private $entityType;

  /**
   * @var \Drupal\renderkit\Formula\Formula_FieldName
   */
  private $fieldNameFormulaProxy;

  public static function cr(string $entity_type_id, string $bundle = NULL) {
    return new Formula_Drilldown(
      Formula_FieldName::fromContainer(),
      new IdToFormula_FieldName_FormatterTypeAndSettings($entity_type_id),
      9);
  }

  /**
   * @param string $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public static function create(string $entityType, string $bundleName = NULL) {

    return new self($entityType, $bundleName);
  }

  /**
   * @param string $entityType
   * @param string|null $bundleName
   */
  public function __construct(string $entityType, string $bundleName = NULL) {

    $this->entityType = $entityType;

    $this->fieldNameFormulaProxy = Formula_FieldName::create(
      $entityType,
      $bundleName);
  }

  /**
   * @param \Donquixote\Ock\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function replacerGetFormula(FormulaReplacerInterface $replacer): FormulaInterface {

    $fieldNameFormula = $replacer->formulaGetReplacement(
      $this->fieldNameFormulaProxy);

    if (!$fieldNameFormula instanceof Formula_SelectInterface) {
      return NULL;
    }

    $idToFormula = new IdToFormula_FieldName_FormatterTypeAndSettings($this->entityType);

    $drilldownFormula = Formula_Drilldown::create($fieldNameFormula, $idToFormula)
      ->withKeys('field', 'display');

    $drilldownValFormula = Formula_DrilldownVal::createArrify($drilldownFormula);

    return $drilldownValFormula;
  }
}
