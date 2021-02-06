<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\IdVal;

use Donquixote\OCUI\Formula\Id\Formula_IdInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBase;

abstract class CfSchema_IdValBase extends Formula_DecoratorBase implements CfSchema_IdValInterface {

  /**
   * Same as parent, but requires an id schema.
   *
   * @param \Donquixote\OCUI\Formula\Id\Formula_IdInterface $decorated
   */
  public function __construct(Formula_IdInterface $decorated) {
    parent::__construct($decorated);
  }

}
