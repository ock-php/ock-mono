<?php
declare(strict_types=1);

namespace Drupal\renderkit\Formula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_Drilldown;
use Donquixote\ObCK\Formula\DrilldownVal\Formula_DrilldownVal;
use Donquixote\ObCK\Formula\Proxy\Replacer\Formula_Proxy_ReplacerInterface;
use Donquixote\ObCK\Formula\Select\Formula_SelectInterface;
use Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface;
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

  /**
   * @param string $entityType
   * @param string|null $bundleName
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public static function create($entityType, $bundleName = NULL) {

    return new self($entityType, $bundleName);
  }

  /**
   * @param string $entityType
   * @param string|null $bundleName
   */
  public function __construct($entityType, $bundleName = NULL) {

    $this->entityType = $entityType;

    $this->fieldNameFormulaProxy = Formula_FieldName::create(
      $entityType,
      $bundleName);
  }

  /**
   * @param \Donquixote\ObCK\FormulaReplacer\FormulaReplacerInterface $replacer
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
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
