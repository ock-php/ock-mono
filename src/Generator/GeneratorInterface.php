<?php

declare(strict_types = 1);

namespace Donquixote\DID\Generator;

use Donquixote\DID\ValueDefinition\ValueDefinitionInterface;

interface GeneratorInterface {

  /**
   * @param \Donquixote\DID\ValueDefinition\ValueDefinitionInterface $definition
   * @param bool $enclose
   *   TRUE to enclose expressions that would otherwise cause ambiguity.
   *
   * @return string
   */
  public function generate(ValueDefinitionInterface $definition, bool $enclose = FALSE): string;

}
