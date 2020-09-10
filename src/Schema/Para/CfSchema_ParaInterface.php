<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Para;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_ParaInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function getParaSchema(): CfSchemaInterface;

}
