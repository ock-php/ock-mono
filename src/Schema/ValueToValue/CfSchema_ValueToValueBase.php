<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\ValueToValue;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Label\CfSchema_Label;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_ValueToValueBase extends CfSchema_DecoratorBase implements CfSchema_ValueToValueInterface {

  /**
   * @param string|null $label
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function withLabel($label): CfSchemaInterface {
    return new CfSchema_Label($this, $label);
  }

}
