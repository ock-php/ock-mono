<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Definitions;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_DefinitionsInterface extends CfSchemaInterface {

  /**
   * @return array[]
   */
  public function getDefinitions(): array;

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
