<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaBase\Decorator;

use Donquixote\Cf\Core\Schema\Base\CfSchemaBaseInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_DecoratorBaseInterface extends CfSchemaBaseInterface {

  /**
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function getDecorated(): CfSchemaInterface;

}
