<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionToLabel;

interface DefinitionToLabelInterface {

  /**
   * @param array $definition
   * @param string|null $else
   *
   * @return string
   */
  public function definitionGetLabel(array $definition, ?string $else): ?string;

}
