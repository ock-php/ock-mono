<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase\Decorator;

use Donquixote\OCUI\Core\Schema\Base\CfSchemaBaseInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface CfSchema_DecoratorBaseInterface extends CfSchemaBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
