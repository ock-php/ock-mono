<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\GroupVal;

use Donquixote\Cf\Schema\Group\CfSchema_GroupInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_GroupValBase extends CfSchema_DecoratorBase implements CfSchema_GroupValInterface {

  /**
   * Same as parent, but must be a group schema.
   *
   * @param \Donquixote\Cf\Schema\Group\CfSchema_GroupInterface $decorated
   */
  public function __construct(CfSchema_GroupInterface $decorated) {
    parent::__construct($decorated);
  }
}
