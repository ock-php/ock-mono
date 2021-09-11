<?php
declare(strict_types=1);

namespace Drupal\renderkit\IdToFormula;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\IdToFormula\IdToFormulaInterface;

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
