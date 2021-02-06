<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Util;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface[] $itemSchemas
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param string $interface
   *
   * @return mixed[]|null
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   */
  public static function getMultiple(array $itemSchemas, SchemaToAnythingInterface $schemaToAnything, string $interface): ?array {

    $itemObjects = [];
    foreach ($itemSchemas as $k => $itemSchema) {
      if (!$itemSchema instanceof FormulaInterface) {
        throw new \RuntimeException("Item schema at key $k must be instance of CfSchemaInterface.");
      }
      $itemCandidate = self::getObject($itemSchema, $schemaToAnything, $interface);
      if (NULL === $itemCandidate) {
        return NULL;
      }
      $itemObjects[$k] = $itemCandidate;
    }

    return $itemObjects;
  }

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param string $interface
   *
   * @return mixed|null
   */
  public static function getObject(FormulaInterface $schema, SchemaToAnythingInterface $schemaToAnything, string $interface) {

    $object = $schemaToAnything->schema($schema, $interface);

    if (NULL === $object || !$object instanceof $interface) {
      return NULL;
    }

    return $object;
  }

}
