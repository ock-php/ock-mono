<?php

declare(strict_types=1);

namespace Donquixote\OCUI\Formula\DecoKey;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\OCUI\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_DecoKeyInterface extends Formula_DecoratorBaseInterface {

  /**
   * @return \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface
   */
  public function getDecorated(): Formula_DrilldownInterface;

  /**
   * @return string
   */
  public function getDecoKey(): string;

  /**
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   */
  public function getDecoratorFormula(): FormulaInterface;

}
