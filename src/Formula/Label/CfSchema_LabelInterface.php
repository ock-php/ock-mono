<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Label;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Formula\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;
use Donquixote\OCUI\Text\TextInterface;

interface CfSchema_LabelInterface extends CfSchemaInterface, CfSchema_DecoratorBaseInterface, CfSchema_SkipEvaluatorInterface {

  /**
   * Gets the label.
   *
   * @return \Donquixote\OCUI\Text\TextInterface|null
   *   The label as a text object, or NULL.
   */
  public function getLabel(): ?TextInterface;

}
