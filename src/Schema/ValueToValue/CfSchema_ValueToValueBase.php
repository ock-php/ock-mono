<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\ValueToValue;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\Label\CfSchema_Label;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;
use Donquixote\OCUI\Text\TextInterface;

abstract class CfSchema_ValueToValueBase extends CfSchema_DecoratorBase implements CfSchema_ValueToValueInterface {

  /**
   * @param \Donquixote\OCUI\Text\TextInterface $label
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function withLabel(TextInterface $label): CfSchemaInterface {
    return new CfSchema_Label($this, $label);
  }

}
