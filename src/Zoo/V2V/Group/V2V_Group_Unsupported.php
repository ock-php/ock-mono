<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\V2V\Group;

use Donquixote\OCUI\Exception\EvaluatorException;
use Donquixote\OCUI\Util\PhpUtil;

class V2V_Group_Unsupported implements V2V_GroupInterface {

  /**
   * {@inheritdoc}
   */
  public function itemsPhpGetPhp(array $itemsPhp): string {
    return PhpUtil::exception(
      EvaluatorException::class,
      'Generation unsupported.');
  }

}
