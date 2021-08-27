<?php

namespace Donquixote\ObCK\Formula\ContextProviding;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;

interface Formula_ContextProvidingInterface extends Formula_SkipEvaluatorInterface {

  /**
   * Gets a context to limit available option.
   *
   * @return \Donquixote\ObCK\Context\CfContextInterface|null
   *   Context, or NULL for no limitations.
   */
  public function getContext(): ?CfContextInterface;

}
