<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface SchemaToAnythingInterface {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or
   *   NULL, if no adapter can be found.
   */
  public function schema(CfSchemaInterface $schema, string $interface);

}
