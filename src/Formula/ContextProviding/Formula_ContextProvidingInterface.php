<?php

namespace Donquixote\OCUI\Formula\ContextProviding;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;

interface Formula_ContextProvidingInterface extends Formula_SkipEvaluatorInterface {

  /**
   * Gets a context to limit available option.
   *
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   *   Context, or NULL for no limitations.
   */
  public function getContext(): ?CfContextInterface;

}
