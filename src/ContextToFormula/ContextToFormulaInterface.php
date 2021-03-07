<?php

namespace Donquixote\OCUI\ContextToFormula;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface ContextToFormulaInterface {

  /**
   * Gets a composition formula for a given context.
   *
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   *   Created formula, or NULL if not applicable with the given context.
   *
   * @throws \Donquixote\OCUI\Exception\FormulaCreationException
   *   Formula cannot be created.
   */
  public function contextGetFormula(CfContextInterface $context = NULL): ?FormulaInterface;

}
