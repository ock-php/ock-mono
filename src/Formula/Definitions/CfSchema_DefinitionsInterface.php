<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Definitions;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

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
