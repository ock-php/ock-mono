<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Defmap;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;

interface CfSchema_DefmapInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface
   */
  public function getDefinitionMap(): DefinitionMapInterface;

  /**
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
