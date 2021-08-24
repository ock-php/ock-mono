<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Definitions;

use Donquixote\ObCK\Context\CfContextInterface;
use Donquixote\ObCK\Core\Formula\FormulaInterface;

interface Formula_DefinitionsInterface extends FormulaInterface {

  /**
   * @return array[]
   */
  public function getDefinitions(): array;

  /**
   * @return \Donquixote\ObCK\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
