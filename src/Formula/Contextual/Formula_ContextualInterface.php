<?php

namespace Donquixote\OCUI\Formula\Contextual;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;

interface Formula_ContextualInterface extends Formula_SkipEvaluatorInterface {

  /**
   * Gets a formula with a context applied.
   *
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *   Context to limit available options, or NULL for no limitations.
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *   Decorated formula with the context applied.
   */
  public function getDecorated(CfContextInterface $context = NULL): FormulaInterface;

}
