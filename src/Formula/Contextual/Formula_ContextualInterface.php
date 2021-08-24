<?php

namespace Donquixote\ObCK\Formula\Contextual;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;

interface Formula_ContextualInterface extends Formula_SkipEvaluatorInterface {

  /**
   * Gets a formula with a context applied.
   *
   * @param \Donquixote\ObCK\Context\CfContextInterface|null $context
   *   Context to limit available options, or NULL for no limitations.
   *
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   *   Decorated formula with the context applied.
   */
  public function getDecorated(CfContextInterface $context = NULL): FormulaInterface;

}
