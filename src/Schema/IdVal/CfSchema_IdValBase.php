<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\IdVal;

use Donquixote\OCUI\Schema\Id\CfSchema_IdInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_IdValBase extends CfSchema_DecoratorBase implements CfSchema_IdValInterface {

  /**
   * Same as parent, but requires an id schema.
   *
   * @param \Donquixote\OCUI\Schema\Id\CfSchema_IdInterface $decorated
   */
  public function __construct(CfSchema_IdInterface $decorated) {
    parent::__construct($decorated);
  }

}
