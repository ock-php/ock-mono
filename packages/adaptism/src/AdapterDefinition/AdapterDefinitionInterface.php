<?php

declare(strict_types=1);

namespace Ock\Adaptism\AdapterDefinition;

use Ock\Egg\Egg\EggInterface;

interface AdapterDefinitionInterface {

  /**
   * Gets the result type, or a common ancestor of all result types.
   *
   * @return class-string|null
   */
  public function getResultType(): ?string;

  /**
   * @return class-string|null
   */
  public function getSourceType(): ?string;

  /**
   * @return int
   */
  public function getSpecifity(): int;

  /**
   * @return \Ock\Egg\Egg\EggInterface<\Ock\Adaptism\SpecificAdapter\SpecificAdapterInterface>
   */
  public function getAdapterEgg(): EggInterface;

}
