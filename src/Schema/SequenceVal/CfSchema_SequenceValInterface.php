<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\SequenceVal;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;
use Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface;

interface CfSchema_SequenceValInterface extends CfSchema_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Cf\Schema\Sequence\CfSchema_SequenceInterface
   */
  public function getDecorated(): CfSchemaInterface;

  /**
   * @return \Donquixote\Cf\Zoo\V2V\Sequence\V2V_SequenceInterface
   */
  public function getV2V(): V2V_SequenceInterface;

}
