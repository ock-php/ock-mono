<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaConfToAnything;

use Donquixote\OCUI\Core\Schema\CfSchemaInterface;

interface SchemaConfToAnythingInterface {

  /**
   * @param \Donquixote\OCUI\Core\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $interface
   *
   * @return object
   *   An instance of $interface.
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public function schema(CfSchemaInterface $schema, $conf, string $interface): object;

}
