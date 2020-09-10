<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\MoreArgsVal;

use Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_MoreArgsValBase extends CfSchema_DecoratorBase implements CfSchema_MoreArgsValInterface {

  /**
   * Same as parent, but must be a MoreArgs schema.
   *
   * @param \Donquixote\Cf\Schema\MoreArgs\CfSchema_MoreArgsInterface $decorated
   */
  public function __construct(CfSchema_MoreArgsInterface $decorated) {
    parent::__construct($decorated);
  }
}
