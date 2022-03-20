<?php

declare(strict_types=1);

namespace Donquixote\Ock\IncarnatorPartialsRegistry;

class IncarnatorPartialsRegistry_Fixed implements IncarnatorPartialsRegistryInterface {

  /**
   * @var \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[]
   */
  private array $partials;

  /**
   * Constructor.
   *
   * @param \Donquixote\Ock\IncarnatorPartial\IncarnatorPartialInterface[] $partials
   */
  public function __construct(array $partials) {
    $this->partials = $partials;
  }

  /**
   * {@inheritdoc}
   */
  public function getPartialsSorted(): array {
    return $this->partials;
  }

}
