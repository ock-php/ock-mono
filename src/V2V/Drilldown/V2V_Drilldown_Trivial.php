<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Drilldown;

class V2V_Drilldown_Trivial implements V2V_DrilldownInterface {

  /**
   * {@inheritdoc}
   */
  public function idPhpGetPhp($id, string $php) {
    return $php;
  }
}
