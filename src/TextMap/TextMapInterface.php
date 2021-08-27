<?php

declare(strict_types=1);

namespace Donquixote\ObCK\TextMap;

interface TextMapInterface {

  /**
   * @return \Donquixote\ObCK\Text\TextInterface[]
   */
  public function getLabels(): array;

}
