<?php

namespace Donquixote\ObCK\ContextToFormula;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface ContextToFormulaInterface {

  /**
   * Gets a composition formula for a given context.
   *
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface|null
   *   Created formula, or NULL if not applicable with the given context.
   *
   * @throws \Donquixote\ObCK\Exception\FormulaCreationException
   *   Formula cannot be created.
   */
  public function contextGetFormula(CfContextInterface $context = NULL): ?FormulaInterface;

}
