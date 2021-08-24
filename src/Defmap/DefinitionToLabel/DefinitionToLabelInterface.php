<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Defmap\DefinitionToLabel;

use Donquixote\ObCK\Text\TextInterface;

interface DefinitionToLabelInterface {

  /**
   * @param array $definition
   * @param string|null $else
   *
   * @return \Donquixote\ObCK\Text\TextInterface|null
   */
  public function definitionGetLabel(array $definition, ?string $else): ?TextInterface;

}
