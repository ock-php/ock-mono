<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Neutral;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Formula\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_NeutralInterface extends CfSchema_ValueToValueBaseInterface, CfSchema_SkipEvaluatorInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
