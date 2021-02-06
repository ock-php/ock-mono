<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Label;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\SkipEvaluator\Formula_SkipEvaluatorInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBaseInterface;
use Donquixote\OCUI\Text\TextInterface;

interface Formula_LabelInterface extends FormulaInterface, Formula_DecoratorBaseInterface, Formula_SkipEvaluatorInterface {

  /**
   * Gets the label.
   *
   * @return \Donquixote\OCUI\Text\TextInterface|null
   *   The label as a text object, or NULL.
   */
  public function getLabel(): ?TextInterface;

}
