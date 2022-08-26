<?php

declare(strict_types = 1);

namespace Donquixote\Ock\Contract;

use Donquixote\Ock\Text\TextInterface;

/**
 * Generic interface for objects that provide a label.
 */
interface LabelHavingInterface {

  public function getLabel(): TextInterface;

}
