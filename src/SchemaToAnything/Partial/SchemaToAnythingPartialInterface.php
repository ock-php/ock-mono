<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaToAnything\Partial;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface;

/**
 * @see \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface
 */
interface SchemaToAnythingPartialInterface {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\Cf\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   */
  public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper);

  /**
   * @param string $resultInterface
   *
   * @return bool
   */
  public function providesResultType(string $resultInterface): bool;

  /**
   * @param string $schemaClass
   *
   * @return bool
   */
  public function acceptsSchemaClass(string $schemaClass): bool;

  /**
   * @return int
   */
  public function getSpecifity(): int;

}
