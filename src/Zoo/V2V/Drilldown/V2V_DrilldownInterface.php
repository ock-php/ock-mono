<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Drilldown;

interface V2V_DrilldownInterface {

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, string $php);

}
