<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;
use Drupal\renderkit\Formula\Formula_EntityId;

class IdToFormula_Et_EntityId implements IdToFormulaInterface {

  /**
   * @param string $entityTypeId
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   */
  public function idGetFormula($entityTypeId): ?FormulaInterface {
    return new Formula_EntityId($entityTypeId);
  }
}
