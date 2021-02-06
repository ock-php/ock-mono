<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\DefaultConf;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_DefaultConfInterface extends FormulaInterface, Formula_DecoratorBaseInterface {

  /**
   * @return mixed
   */
  public function getDefaultConf();

}
