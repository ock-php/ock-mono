<?php
declare(strict_types=1);

namespace Donquixote\ObCK\V2V\Group;

use Donquixote\ObCK\Exception\EvaluatorException;
use Donquixote\ObCK\Util\PhpUtil;

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
