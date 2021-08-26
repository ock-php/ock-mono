<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Formula\DecoKey;

use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\ObCK\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

interface Formula_DecoKeyInterface extends Formula_DecoratorBaseInterface {

  /**
   * @return \Donquixote\ObCK\Formula\Drilldown\Formula_DrilldownInterface
   *
   * @todo Does this need to be a drilldown?
   */
  public function getDecorated(): Formula_DrilldownInterface;

  /**
   * @return string
   */
  public function getDecoKey(): string;

  /**
   * @return \Donquixote\ObCK\Core\Formula\FormulaInterface
   */
  public function getDecoratorFormula(): FormulaInterface;

}
