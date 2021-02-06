<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Zoo\ValueStub;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaConfToAnything\SchemaConfToAnythingInterface;

class ValueStub {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param mixed $conf
   * @param \Donquixote\OCUI\SchemaConfToAnything\SchemaConfToAnythingInterface $scta
   *
   * @return null|\Donquixote\OCUI\Zoo\ValueStub\ValueStubInterface
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function fromSchemaConf(FormulaInterface $schema, $conf, SchemaConfToAnythingInterface $scta): ?ValueStubInterface {

    $object = $scta->schema($schema, $conf, ValueStubInterface::class);

    if (NULL === $object || !$object instanceof ValueStubInterface) {
      return NULL;
    }

    return $object;
  }

}
