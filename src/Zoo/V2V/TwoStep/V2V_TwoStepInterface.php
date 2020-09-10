<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\TwoStep;

interface V2V_TwoStepInterface {

  /**
   * @param mixed $firstStepValue
   *   Value from the first step of configuration.
   * @param mixed $secondStepValue
   *   Value from the second step of configuration.
   *
   * @return mixed
   *   The final value.
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue($firstStepValue, $secondStepValue);

  /**
   * @param string $firstItemPhp
   * @param string $secondItemPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(string $firstItemPhp, string $secondItemPhp): string;

}
