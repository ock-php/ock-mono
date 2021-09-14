<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\Label;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBaseInterface;
use Donquixote\Ock\Text\TextInterface;

interface Formula_LabelInterface extends FormulaInterface, Formula_DecoratorBaseInterface, Formula_SkipEvaluatorInterface {

  /**
   * Gets the label.
   *
   * @return \Donquixote\Ock\Text\TextInterface|null
   *   The label as a text object, or NULL.
   */
  public function getLabel(): ?TextInterface;

}
