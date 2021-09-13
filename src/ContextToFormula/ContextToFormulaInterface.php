<?php

namespace Donquixote\Ock\ContextToFormula;

use Donquixote\Ock\Context\CfContextInterface;
use Donquixote\Ock\Core\Formula\FormulaInterface;

interface ContextToFormulaInterface {

  /**
   * Gets a composition formula for a given context.
   *
   * @param \Donquixote\Ock\Context\CfContextInterface|null $context
   *   Context to constrain available options.
   *
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface|null
   *   Created formula, or NULL if not applicable with the given context.
   *
   * @throws \Donquixote\Ock\Exception\FormulaCreationException
   *   Formula cannot be created.
   */
  public function contextGetFormula(CfContextInterface $context = NULL): ?FormulaInterface;

}
