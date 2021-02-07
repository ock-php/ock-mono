<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface TypeToFormulaInterface {

  /**
   * @param string $type
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function typeGetFormula(string $type, CfContextInterface $context = NULL): FormulaInterface;

}
