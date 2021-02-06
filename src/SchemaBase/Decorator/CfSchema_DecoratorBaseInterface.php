<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaBase\Decorator;

use Donquixote\OCUI\Core\Formula\Base\CfSchemaBaseInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

interface CfSchema_DecoratorBaseInterface extends CfSchemaBaseInterface {

  /**
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
