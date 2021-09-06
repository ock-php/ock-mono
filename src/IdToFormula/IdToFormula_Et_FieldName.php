<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Drupal\renderkit\Formula\Formula_FieldName_AllowedTypes;

class IdToFormula_Et_FieldName implements IdToFormulaInterface {

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @var null|string
   */
  private $bundleName;

  /**
   * @param null|string[] $allowedFieldTypes
   * @param string|null $bundleName
   */
  public function __construct(
    array $allowedFieldTypes = NULL,
    $bundleName = NULL
  ) {
    $this->allowedFieldTypes = $allowedFieldTypes;
    $this->bundleName = $bundleName;
  }

  /**
   * @param string|int $entityTypeId
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($entityTypeId): ?FormulaInterface {

    return new Formula_FieldName_AllowedTypes(
      $entityTypeId,
      $this->bundleName,
      $this->allowedFieldTypes);
  }
}
