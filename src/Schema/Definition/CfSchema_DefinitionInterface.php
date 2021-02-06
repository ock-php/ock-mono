<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Definition;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface CfSchema_DefinitionInterface extends CfSchemaInterface {

  /**
   * @return array
   */
  public function getDefinition(): array;

  /**
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
