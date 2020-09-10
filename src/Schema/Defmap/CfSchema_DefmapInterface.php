<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Defmap;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;

interface CfSchema_DefmapInterface extends CfSchemaInterface {

  /**
   * @return \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface
   */
  public function getDefinitionMap(): DefinitionMapInterface;

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

}
