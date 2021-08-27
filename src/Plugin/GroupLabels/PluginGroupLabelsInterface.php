<?php

declare(strict_types=1);

namespace Donquixote\ObCK\Plugin\GroupLabels;

interface PluginGroupLabelsInterface {

  /**
   * @return \Donquixote\ObCK\Text\TextInterface[]
   */
  public function getLabels(): array;

}
