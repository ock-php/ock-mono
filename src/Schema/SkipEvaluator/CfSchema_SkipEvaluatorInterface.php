<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\SkipEvaluator;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

/**
 * Base interface for all schema decorators that have no effect on evaluators.
 */
interface CfSchema_SkipEvaluatorInterface extends CfSchema_DecoratorBaseInterface, CfSchemaInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
