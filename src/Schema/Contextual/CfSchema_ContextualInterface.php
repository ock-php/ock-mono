<?php

namespace Donquixote\OCUI\Schema\Contextual;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;

interface CfSchema_ContextualInterface extends CfSchema_SkipEvaluatorInterface {

  /**
   * Gets a schema with a context applied.
   *
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *   Context to limit available options, or NULL for no limitations.
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   *   Decorated schema with the context applied.
   */
  public function getDecorated(CfContextInterface $context = NULL): CfSchemaInterface;

}
