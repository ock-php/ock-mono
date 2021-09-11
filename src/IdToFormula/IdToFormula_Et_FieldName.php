<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Drupal\renderkit\Formula\Formula_FieldName;
use Drupal\renderkit\Formula\Formula_FieldName_AllowedTypes;

class IdToFormula_Et_FieldName implements IdToFormulaInterface {

  /**
   * @var null|string[]
   */
  private $allowedFieldTypes;

  /**
   * @param null|string[] $allowedFieldTypes
   */
  public function __construct(array $allowedFieldTypes = NULL) {
    $this->allowedFieldTypes = $allowedFieldTypes;
  }

  /**
   * @param string|int $id
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($id): ?FormulaInterface {
    return Formula_FieldName::create(
      0,
      0,
      0,
      $id,
      NULL,
      $this->allowedFieldTypes);
  }
}
