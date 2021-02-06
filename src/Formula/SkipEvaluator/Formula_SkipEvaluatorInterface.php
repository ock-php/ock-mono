<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\SkipEvaluator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

/**
 * Base interface for all schema decorators that have no effect on evaluators.
 */
interface Formula_SkipEvaluatorInterface extends CfSchema_DecoratorBaseInterface, FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
