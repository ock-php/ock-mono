<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Definition;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_DefinitionInterface extends CfSchemaInterface {

  /**
   * @return array
   */
  public function getDefinition(): array;

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
