<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Label;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_LabelInterface extends CfSchemaInterface, CfSchema_DecoratorBaseInterface, CfSchema_SkipEvaluatorInterface {

  /**
   * Gets the label.
   *
   * @return string|object|null
   *   The label as a string or stringable object, or NULL.
   */
  public function getLabel();

}
