<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToLabel;

use Donquixote\OCUI\Text\TextInterface;

interface DefinitionToLabelInterface {

  /**
   * @param array $definition
   * @param string|null $else
   *
   * @return \Donquixote\OCUI\Text\TextInterface|null
   */
  public function definitionGetLabel(array $definition, ?string $else): ?TextInterface;

}
