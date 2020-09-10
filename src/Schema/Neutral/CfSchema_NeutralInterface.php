<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Neutral;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\SkipEvaluator\CfSchema_SkipEvaluatorInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_NeutralInterface extends CfSchema_ValueToValueBaseInterface, CfSchema_SkipEvaluatorInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
