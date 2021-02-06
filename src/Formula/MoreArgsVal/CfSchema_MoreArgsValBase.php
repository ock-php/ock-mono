<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\MoreArgsVal;

use Donquixote\OCUI\Formula\MoreArgs\CfSchema_MoreArgsInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBase;

abstract class CfSchema_MoreArgsValBase extends Formula_DecoratorBase implements CfSchema_MoreArgsValInterface {

  /**
   * Same as parent, but must be a MoreArgs schema.
   *
   * @param \Donquixote\OCUI\Formula\MoreArgs\CfSchema_MoreArgsInterface $decorated
   */
  public function __construct(CfSchema_MoreArgsInterface $decorated) {
    parent::__construct($decorated);
  }
}
