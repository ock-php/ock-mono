<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Neutral;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\ObCK\FormulaBase\Formula_ValueToValueBaseInterface;

interface Formula_NeutralInterface extends Formula_ValueToValueBaseInterface, Formula_SkipEvaluatorInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
