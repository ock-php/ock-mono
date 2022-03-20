<?php

declare(strict_types = 1);

namespace Donquixote\Ock\IncarnatorPartial\Registry;

interface IncarnatorPartialsRegistryInterface {

  /**
   * @return list<\Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface>
   *
   * @throws \Exception
   */
  public function getPartials(): array;

}
