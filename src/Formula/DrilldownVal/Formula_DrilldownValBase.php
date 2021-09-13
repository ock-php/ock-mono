<?php
declare(strict_types=1);

namespace Donquixote\Ock\Formula\DrilldownVal;

use Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\Ock\FormulaBase\Decorator\Formula_DecoratorBase;

abstract class Formula_DrilldownValBase extends Formula_DecoratorBase implements Formula_DrilldownValInterface {

  /**
   * Same as parent constructor, but the decorated formula must be a drilldown.
   *
   * @param \Donquixote\Ock\Formula\Drilldown\Formula_DrilldownInterface $decorated
   */
  public function __construct(Formula_DrilldownInterface $decorated) {
    parent::__construct($decorated);
  }

}
