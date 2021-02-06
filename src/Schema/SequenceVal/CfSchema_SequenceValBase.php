<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\SequenceVal;

use Donquixote\OCUI\Schema\Sequence\CfSchema_SequenceInterface;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_SequenceValBase extends CfSchema_DecoratorBase implements CfSchema_SequenceValInterface {

  /**
   * Same as parent, but requires a sequence schema.
   *
   * @param \Donquixote\OCUI\Schema\Sequence\CfSchema_SequenceInterface $decorated
   */
  public function __construct(CfSchema_SequenceInterface $decorated) {
    parent::__construct($decorated);
  }

}
