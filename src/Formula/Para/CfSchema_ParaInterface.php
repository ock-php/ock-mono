<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Para;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\CfSchema_ValueToValueBaseInterface;

interface CfSchema_ParaInterface extends CfSchema_ValueToValueBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public function getParaSchema(): CfSchemaInterface;

}
