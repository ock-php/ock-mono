<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\ValueToValue;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Formula\Label\CfSchema_Label;
use Donquixote\OCUI\SchemaBase\Decorator\CfSchema_DecoratorBase;
use Donquixote\OCUI\Text\TextInterface;

abstract class CfSchema_ValueToValueBase extends CfSchema_DecoratorBase implements CfSchema_ValueToValueInterface {

  /**
   * @param \Donquixote\OCUI\Text\TextInterface $label
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface
   */
  public function withLabel(TextInterface $label): CfSchemaInterface {
    return new CfSchema_Label($this, $label);
  }

}
