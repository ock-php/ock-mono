<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Proxy\Replacer;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

interface CfSchema_Proxy_ReplacerInterface extends CfSchemaInterface {

  /**
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface
   */
  public function replacerGetSchema(SchemaReplacerInterface $replacer): CfSchemaInterface;

}
