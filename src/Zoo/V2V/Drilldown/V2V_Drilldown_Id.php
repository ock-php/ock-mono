<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Drilldown;

class V2V_Drilldown_Id implements V2V_DrilldownInterface {

  /**
   * {@inheritdoc}
   */
  public function idValueGetValue($id, $value) {
    return $id;
  }

  /**
   * {@inheritdoc}
   */
  public function idPhpGetPhp($id, string $php) {
    return var_export($id, TRUE);
  }
}
