<?php

namespace Donquixote\OCUI\Schema\ContextProviding;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;

interface CfSchema_ContextProvidingInterface extends CfSchema_SkipEvaluatorInterface {

  /**
   * Gets a context to limit available option.
   *
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   *   Context, or NULL for no limitations.
   */
  public function getContext(): ?CfContextInterface;

}
