<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinition;

use Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface;

class AdapterDefinition_Simple implements AdapterDefinitionInterface {

  /**
   * Constructor.
   *
   * @param class-string|null $sourceType
   * @param class-string|null $resultType
   * @param int $specifity
   * @param \Donquixote\Adaptism\AdapterFromContainer\AdapterFromContainerInterface $adapterFromContainer
   */
  public function __construct(
    private readonly ?string $sourceType,
    private readonly ?string $resultType,
    private readonly int $specifity,
    private readonly AdapterFromContainerInterface $adapterFromContainer,
  ) {}

  public function getResultType(): ?string {
    return $this->resultType;
  }

  public function getSourceType(): ?string {
    return $this->sourceType;
  }

  public function getSpecifity(): int {
    return $this->specifity;
  }

  public function getFactory(): AdapterFromContainerInterface {
    return $this->adapterFromContainer;
  }

}
