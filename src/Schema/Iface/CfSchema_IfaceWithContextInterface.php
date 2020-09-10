<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Iface;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface CfSchema_IfaceWithContextInterface extends CfSchemaInterface {

  /**
   * @return string
   */
  public function getInterface(): string;

  /**
   * @return \Donquixote\Cf\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

  /**
   * @return string
   */
  public function getCacheId(): string;

}
