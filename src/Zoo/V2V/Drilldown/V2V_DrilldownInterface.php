<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Drilldown;

interface V2V_DrilldownInterface {

  /**
   * @param string|int $id
   * @param mixed $value
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function idValueGetValue($id, $value);

  /**
   * @param string|int $id
   * @param string $php
   *
   * @return mixed
   */
  public function idPhpGetPhp($id, string $php);

}
