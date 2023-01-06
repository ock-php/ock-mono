<?php

declare(strict_types = 1);

namespace Donquixote\DID\ValueDefinition;

class ValueDefinition_Parametric implements ValueDefinitionInterface {

  /**
   * Constructor.
   *
   * @param mixed|\Donquixote\DID\ValueDefinition\ValueDefinitionInterface $value
   *   Value definition that may contain free arguments.
   */
  public function __construct(
    public readonly mixed $value,
  ) {}

}
