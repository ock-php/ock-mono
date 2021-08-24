<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Definition;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface Formula_DefinitionInterface extends FormulaInterface {

  /**
   * @return array
   */
  public function getDefinition(): array;

  /**
   * @return \Donquixote\ObCK\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
