<?php
declare(strict_types=1);

namespace Donquixote\OCUI\ParamToLabel;

use Donquixote\OCUI\Util\StringUtil;

class ParamToLabel implements ParamToLabelInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetLabel(\ReflectionParameter $param): ?string {
    return StringUtil::methodNameGenerateLabel($param->getName());
  }
}
