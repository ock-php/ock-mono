<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaToAnything;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

interface SchemaToAnythingInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $schema
   * @param string $interface
   *
   * @return object|null
   *   An instance of $interface, or
   *   NULL, if no adapter can be found.
   */
  public function schema(CfSchemaInterface $schema, string $interface);

}
