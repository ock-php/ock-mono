<?php

declare(strict_types=1);

namespace Donquixote\Ock\Formula\DecoKey;

use Donquixote\Ock\Core\Formula\FormulaInterface;
use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBaseInterface;

/**
 * @see \Donquixote\Ock\Formula\DecoShift\Formula_DecoShiftInterface
 */
interface Formula_DecoKeyInterface extends Formula_DecoratorBaseInterface {

  /**
   * @return \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface
   *
   * @todo Does this need to be a drilldown?
   */
  public function getDecorated(): Formula_DrilldownInterface;

  /**
   * @return string
   */
  public function getDecoKey(): string;

  /**
   * @return \Donquixote\Ock\Core\Formula\FormulaInterface
   */
  public function getDecoratorFormula(): FormulaInterface;

}
