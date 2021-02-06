<?php

namespace Donquixote\Cf\Schema\ContextProviding;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;

interface CfSchema_ContextProvidingInterface extends CfSchema_SkipEvaluatorInterface {

  /**
   * Gets a context to limit available option.
   *
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   *   Context, or NULL for no limitations.
   */
  public function getContext(): ?CfContextInterface;

}
