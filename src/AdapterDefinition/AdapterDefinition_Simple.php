<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinition;

use Donquixote\DID\ContainerToValue\ContainerToValueInterface;

class AdapterDefinition_Simple implements AdapterDefinitionInterface {

  /**
   * Constructor.
   *
   * @param class-string|null $sourceType
   * @param class-string|null $resultType
   * @param int $specifity
   * @param ContainerToValueInterface<\Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface> $adapterCTV
   */
  public function __construct(
    private readonly ?string $sourceType,
    private readonly ?string $resultType,
    private readonly int $specifity,
    private readonly ContainerToValueInterface $adapterCTV,
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

  public function getAdapterCTV(): ContainerToValueInterface {
    return $this->adapterCTV;
  }

}
