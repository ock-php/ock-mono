<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Boolean;

interface V2V_BooleanInterface {

  /**
   * @return mixed
   */
  public function getTrueValue();

  /**
   * @return mixed
   */
  public function getFalseValue();

  /**
   * @return string
   */
  public function getTruePhp(): string;

  /**
   * @return string
   */
  public function getFalsePhp(): string;

}
