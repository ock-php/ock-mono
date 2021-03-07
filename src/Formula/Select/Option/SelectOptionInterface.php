<?php

namespace Donquixote\OCUI\Formula\Select\Option;

use Donquixote\OCUI\Text\TextInterface;

interface SelectOptionInterface {

  public function getLabel(): ?TextInterface;

  public function getGroupLabel(): ?TextInterface;

}
