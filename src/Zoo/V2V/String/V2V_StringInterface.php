<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\String;

interface V2V_StringInterface {

  /**
   * @param string $string
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function stringGetValue(string $string);

  /**
   * @param string $string
   *
   * @return string
   */
  public function stringGetPhp(string $string): string;

}
