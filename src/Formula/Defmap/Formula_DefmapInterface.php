<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Defmap;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;
use Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface;

interface Formula_DefmapInterface extends FormulaInterface {

  /**
   * @return \Donquixote\ObCK\Defmap\DefinitionMap\DefinitionMapInterface
   */
  public function getDefinitionMap(): DefinitionMapInterface;

  /**
   * @return \Donquixote\ObCK\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
