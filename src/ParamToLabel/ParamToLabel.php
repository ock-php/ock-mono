<?php

declare(strict_types=1);

namespace Donquixote\Ock\ParamToLabel;

use Donquixote\Adaptism\Attribute\Service;
use Donquixote\Ock\Text\Text;
use Donquixote\Ock\Text\TextInterface;
use Donquixote\Ock\Util\StringUtil;

#[Service]
class ParamToLabel implements ParamToLabelInterface {

  /**
   * {@inheritdoc}
   */
  public function paramGetLabel(\ReflectionParameter $param): ?TextInterface {
    return Text::t(
      StringUtil::methodNameGenerateLabel($param->getName()),
    );
  }

}
