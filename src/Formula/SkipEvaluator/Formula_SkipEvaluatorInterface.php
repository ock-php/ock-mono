<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\SkipEvaluator;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

/**
 * Base interface for all formula decorators that have no effect on evaluators.
 */
interface Formula_SkipEvaluatorInterface extends Formula_DecoratorBaseInterface, FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
