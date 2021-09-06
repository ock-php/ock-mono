<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\IdToFormula\IdToFormulaInterface;

class IdToFormula_DrupalPlugin implements IdToFormulaInterface {

  public function __construct() {
  }

  /**
   * @inheritDoc
   */
  public function idGetFormula($id): ?FormulaInterface {
    // TODO: Implement idGetFormula() method.
  }

}
