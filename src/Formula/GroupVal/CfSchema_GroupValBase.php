<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\GroupVal;

use Donquixote\OCUI\Formula\Group\CfSchema_GroupInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_GroupValBase extends CfSchema_DecoratorBase implements CfSchema_GroupValInterface {

  /**
   * Same as parent, but must be a group schema.
   *
   * @param \Donquixote\OCUI\Formula\Group\CfSchema_GroupInterface $decorated
   */
  public function __construct(CfSchema_GroupInterface $decorated) {
    parent::__construct($decorated);
  }
}
