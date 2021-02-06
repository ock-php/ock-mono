<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaToAnything\Partial;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface;

/**
 * @see \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface
 */
interface SchemaToAnythingPartialInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $schema
   * @param string $interface
   * @param \Donquixote\OCUI\SchemaToAnything\SchemaToAnythingInterface $helper
   *
   * @return null|object
   *   An instance of $interface, or NULL.
   *
   * @throws \Donquixote\OCUI\Exception\SchemaToAnythingException
   *   Malfunction in a schema replacer.
   */
  public function schema(
    CfSchemaInterface $schema,
    string $interface,
    SchemaToAnythingInterface $helper): ?object;

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
