<?php

declare(strict_types=1);

namespace Donquixote\DID\Contract;

/**
 * The object can provide a service id for a container.
 */
interface ServiceIdHavingInterface {

  /**
   * Gets a service id for a DI container.
   *
   * @return string|null
   */
  public function getServiceId(): ?string;

}
