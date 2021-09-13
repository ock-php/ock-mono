<?php

namespace Donquixote\Ock\Formula\Select\Option;

use Donquixote\Ock\Text\TextInterface;

interface SelectOptionInterface {

  public function getLabel(): ?TextInterface;

  public function getGroupLabel(): ?TextInterface;

}
