<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Sequence;

use Donquixote\OCUI\Util\PhpUtil;

class V2V_Sequence_Trivial implements V2V_SequenceInterface {

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return PhpUtil::phpArray($itemsPhp);
  }
}
