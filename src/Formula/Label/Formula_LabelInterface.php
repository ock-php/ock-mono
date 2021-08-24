<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Label;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBaseInterface;
use Donquixote\ObCK\Text\TextInterface;

interface Formula_LabelInterface extends FormulaInterface, Formula_DecoratorBaseInterface, Formula_SkipEvaluatorInterface {

  /**
   * Gets the label.
   *
   * @return \Donquixote\ObCK\Text\TextInterface|null
   *   The label as a text object, or NULL.
   */
  public function getLabel(): ?TextInterface;

}
