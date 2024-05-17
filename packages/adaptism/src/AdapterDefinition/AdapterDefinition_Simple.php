<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinition;

use Ock\Egg\Egg\EggInterface;

class AdapterDefinition_Simple implements AdapterDefinitionInterface {

  /**
   * Constructor.
   *
   * @param class-string|null $sourceType
   * @param class-string|null $resultType
   * @param int $specifity
   * @param EggInterface<\Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface> $adapterCTV
   */
  public function __construct(
    private readonly ?string $sourceType,
    private readonly ?string $resultType,
    private readonly int $specifity,
    private readonly EggInterface $adapterCTV,
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

  public function getAdapterCTV(): EggInterface {
    return $this->adapterCTV;
  }

}
