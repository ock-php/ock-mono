<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Defmap;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;

interface Formula_DefmapInterface extends FormulaInterface {

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface
   */
  public function getDefinitionMap(): DefinitionMapInterface;

  /**
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
