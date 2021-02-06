<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\MoreArgsVal;

use Donquixote\OCUI\Schema\MoreArgs\CfSchema_MoreArgsInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_MoreArgsValBase extends CfSchema_DecoratorBase implements CfSchema_MoreArgsValInterface {

  /**
   * Same as parent, but must be a MoreArgs schema.
   *
   * @param \Donquixote\OCUI\Schema\MoreArgs\CfSchema_MoreArgsInterface $decorated
   */
  public function __construct(CfSchema_MoreArgsInterface $decorated) {
    parent::__construct($decorated);
  }
}
