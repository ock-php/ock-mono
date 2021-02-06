<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Proxy\Replacer;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

interface CfSchema_Proxy_ReplacerInterface extends CfSchemaInterface {

  /**
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Schema\CfSchemaInterface
   */
  public function replacerGetSchema(SchemaReplacerInterface $replacer): CfSchemaInterface;

}
