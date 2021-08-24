<?php
declare(strict_types=1);

namespace Donquixote\ObCK\ParamToLabel;

use Donquixote\ObCK\Util\StringUtil;

class ParamToLabel implements ParamToLabelInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetLabel(\ReflectionParameter $param): ?string {
    return StringUtil::methodNameGenerateLabel($param->getName());
  }
}
