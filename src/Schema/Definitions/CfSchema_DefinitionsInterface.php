<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Definitions;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface CfSchema_DefinitionsInterface extends CfSchemaInterface {

  /**
   * @return array[]
   */
  public function getDefinitions(): array;

  /**
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
