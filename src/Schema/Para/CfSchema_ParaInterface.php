<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Para;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_ParaInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function getParaSchema(): CfSchemaInterface;

}
