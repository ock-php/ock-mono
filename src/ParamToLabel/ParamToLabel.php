<?php
declare(strict_types=1);

namespace Donquixote\Cf\ParamToLabel;

use Donquixote\Cf\Util\StringUtil;

class ParamToLabel implements ParamToLabelInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetLabel(\ReflectionParameter $param): ?string {
    return StringUtil::methodNameGenerateLabel($param->getName());
  }
}
