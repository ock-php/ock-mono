<?php

declare(strict_types=1);

namespace Donquixote\Ock\ParamToLabel;

use Donquixote\Ock\Text\TextInterface;

/**
 * A service to auto-generate a label from a reflection parameter.
 */
interface ParamToLabelInterface {

  /**
   * @param \ReflectionParameter $param
   *
   * @return \Donquixote\Ock\Text\TextInterface|null
   */
  public function paramGetLabel(\ReflectionParameter $param): ?TextInterface;

}
