<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\V2V\Group;

interface V2V_GroupInterface {

  /**
   * @param mixed[] $values
   *   Format: $[$groupItemKey] = $groupItemValue
   *
   * @return mixed
   *
   * @throws \Donquixote\Cf\Exception\EvaluatorException
   */
  public function valuesGetValue(array $values);

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp): string;

}
