<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\DefaultConf;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_DefaultConfInterface extends CfSchemaInterface, CfSchema_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getDefaultConf();

}
