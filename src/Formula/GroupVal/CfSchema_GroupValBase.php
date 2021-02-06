<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\GroupVal;

use Donquixote\OCUI\Formula\Group\Formula_GroupInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBase;

abstract class CfSchema_GroupValBase extends Formula_DecoratorBase implements CfSchema_GroupValInterface {

  /**
   * Same as parent, but must be a group schema.
   *
   * @param \Donquixote\OCUI\Formula\Group\Formula_GroupInterface $decorated
   */
  public function __construct(Formula_GroupInterface $decorated) {
    parent::__construct($decorated);
  }
}
