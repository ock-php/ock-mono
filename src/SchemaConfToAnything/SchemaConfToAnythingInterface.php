<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaConfToAnything;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface SchemaConfToAnythingInterface {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param string $interface
   *
   * @return object
   *   An instance of $interface.
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public function schema(CfSchemaInterface $schema, $conf, $interface): object;

}
