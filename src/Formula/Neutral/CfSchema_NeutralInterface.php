<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\OCUI\SchemaBase\Formula_ValueToValueBaseInterface;

interface CfSchema_NeutralInterface extends Formula_ValueToValueBaseInterface, Formula_SkipEvaluatorInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
