<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\SkipEvaluator;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

/**
 * Base interface for all formula decorators that have no effect on evaluators.
 */
interface Formula_SkipEvaluatorInterface extends Formula_DecoratorBaseInterface, FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getDecorated(): FormulaInterface;

}
