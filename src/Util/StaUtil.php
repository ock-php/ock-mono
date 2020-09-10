<?php
declare(strict_types=1);

namespace Donquixote\Cf\Util;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Emptiness\EmptinessInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

final class StaUtil extends UtilBase {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   *
   * @return \Donquixote\Cf\Emptiness\EmptinessInterface|null
   */
  public static function emptinessOrNull(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything): ?EmptinessInterface {
    return self::getObject($schema, $schemaToAnything, EmptinessInterface::class);
  }

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface[] $itemSchemas
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param string $interface
   *
   * @return mixed[]|null
   * @throws \Donquixote\Cf\Exception\SchemaToAnythingException
   */
  public static function getMultiple(array $itemSchemas, SchemaToAnythingInterface $schemaToAnything, $interface): ?array {

    $itemObjects = [];
    foreach ($itemSchemas as $k => $itemSchema) {
      if (!$itemSchema instanceof CfSchemaInterface) {
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
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $schemaToAnything
   * @param string $interface
   *
   * @return mixed|null
   */
  public static function getObject(CfSchemaInterface $schema, SchemaToAnythingInterface $schemaToAnything, $interface) {

    $object = $schemaToAnything->schema($schema, $interface);

    if (NULL === $object || !$object instanceof $interface) {
      return NULL;
    }

    return $object;
  }

}
