<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Neutral;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_NeutralInterface extends CfSchema_ValueToValueBaseInterface, CfSchema_SkipEvaluatorInterface {

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
