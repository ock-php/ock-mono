<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\DefaultConf;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBaseInterface;

interface CfSchema_DefaultConfInterface extends CfSchemaInterface, CfSchema_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getDefaultConf();

}
