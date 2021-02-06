<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\SkipEvaluator;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

/**
 * Base interface for all schema decorators that have no effect on evaluators.
 */
interface CfSchema_SkipEvaluatorInterface extends CfSchema_DecoratorBaseInterface, CfSchemaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
