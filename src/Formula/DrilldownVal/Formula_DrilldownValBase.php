<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\DrilldownVal;

use Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface;
use Donquixote\OCUI\SchemaBase\Decorator\Formula_DecoratorBase;

abstract class Formula_DrilldownValBase extends Formula_DecoratorBase implements Formula_DrilldownValInterface {

  /**
   * Same as parent constructor, but the decorated schema must be a drilldown.
   *
   * @param \Donquixote\OCUI\Formula\Drilldown\Formula_DrilldownInterface $decorated
   */
  public function __construct(Formula_DrilldownInterface $decorated) {
    parent::__construct($decorated);
  }

}
