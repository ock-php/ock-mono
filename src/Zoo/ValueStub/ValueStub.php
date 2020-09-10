<?php
declare(strict_types=1);

namespace Donquixote\Cf\Zoo\ValueStub;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface;

class ValueStub {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\Cf\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\Cf\Zoo\ValueStub\ValueStubInterface
   *
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function fromSchemaConf(CfSchemaInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueStubInterface {

    $object = $scta->schema($schema, $conf, ValueStubInterface::class);

    if (NULL === $object || !$object instanceof ValueStubInterface) {
      return NULL;
    }

    return $object;
  }

}
