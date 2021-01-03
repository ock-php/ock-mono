<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionToLabel;

use Donquixote\Cf\Text\TextInterface;

interface DefinitionToLabelInterface {

  /**
   * @param array $definition
   * @param string|null $else
   *
   * @return \Donquixote\Cf\Text\TextInterface|null
   */
  public function definitionGetLabel(array $definition, ?string $else): ?TextInterface;

}
