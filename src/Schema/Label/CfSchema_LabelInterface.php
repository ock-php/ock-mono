<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Label;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;
use Donquixote\Cf\Text\TextInterface;

interface CfSchema_LabelInterface extends CfSchemaInterface, CfSchema_DecoratorBaseInterface, CfSchema_SkipEvaluatorInterface {

  /**
   * Gets the label.
   *
   * @return \Donquixote\Cf\Text\TextInterface|null
   *   The label as a text object, or NULL.
   */
  public function getLabel(): ?TextInterface;

}
