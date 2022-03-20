<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartialsRegistry;

interface IncarnatorPartialsRegistryInterface {

  /**
   * Gets a sorted list of partials.
   *
   * @return \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   *   Sorted partials, starting with highest specifity / priority.
   *   Array keys are irrelevant, typically sequential.
   */
  public function getPartialsSorted(): array;

}
