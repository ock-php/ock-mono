<?php

namespace Donquixote\ObCK\Formula\Select\Option;

use Donquixote\ObCK\Text\TextInterface;

interface SelectOptionInterface {

  public function getLabel(): ?TextInterface;

  public function getGroupLabel(): ?TextInterface;

}
