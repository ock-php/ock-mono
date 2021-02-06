<?php

namespace Donquixote\Cf\Schema\Contextual;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;

interface CfSchema_ContextualInterface extends CfSchema_SkipEvaluatorInterface {

  /**
   * Gets a schema with a context applied.
   *
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   *   Context to limit available options, or NULL for no limitations.
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   *   Decorated schema with the context applied.
   */
  public function getDecorated(CfContextInterface $context = NULL): CfSchemaInterface;

}
