<?php

declare(strict_types = 1);

namespace Donquixote\DID\ValueDefinition;

/**
 * Abstract interface with no methods.
 *
 * Every implementation will have its own methods.
 */
final class ValueDefinition_GetService implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param string $id
   */
  public function __construct(
    public readonly string $id,
  ) {}

}
