<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\DrilldownVal;

use Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface;
use Donquixote\Cf\SchemaBase\Decorator\CfSchema_DecoratorBase;

abstract class CfSchema_DrilldownValBase extends CfSchema_DecoratorBase implements CfSchema_DrilldownValInterface {

  /**
   * Same as parent constructor, but the decorated schema must be a drilldown.
   *
   * @param \Donquixote\Cf\Schema\Drilldown\CfSchema_DrilldownInterface $decorated
   */
  public function __construct(CfSchema_DrilldownInterface $decorated) {
    parent::__construct($decorated);
  }

}
