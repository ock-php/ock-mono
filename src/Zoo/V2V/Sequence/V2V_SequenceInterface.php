<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Sequence;

interface V2V_SequenceInterface {

  /**
   * @param string[] $itemsPhp
   *
   * @return string
   */
  public function itemsPhpGetPhp(array $itemsPhp): string;

}
