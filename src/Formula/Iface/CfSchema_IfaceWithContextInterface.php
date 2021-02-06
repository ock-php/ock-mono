<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Iface;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface CfSchema_IfaceWithContextInterface extends CfSchemaInterface {

  /**
   * @return string
   */
  public function getInterface(): string;

  /**
   * @return \Donquixote\OCUI\Context\CfContextInterface|null
   */
  public function getContext(): ?CfContextInterface;

  /**
   * @return string
   */
  public function getCacheId(): string;

}
