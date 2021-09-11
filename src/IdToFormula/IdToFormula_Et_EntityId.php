<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;
use Drupal\renderkit\Formula\Formula_EntityId;

class IdToFormula_Et_EntityId implements IdToFormulaInterface {

  /**
   * @param string $entityTypeId
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($entityTypeId): ?FormulaInterface {
    return new Formula_EntityId($entityTypeId);
  }
}
