<?php

declare(strict_types=1);

namespace Donquixote\Adaptism\AdapterDefinition;

use Donquixote\DID\ContainerToValue\ContainerToValueInterface;

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
   * @return \Donquixote\DID\ContainerToValue\ContainerToValueInterface<\Donquixote\Adaptism\SpecificAdapter\SpecificAdapterInterface>
   */
  public function getAdapterCTV(): ContainerToValueInterface;

}
