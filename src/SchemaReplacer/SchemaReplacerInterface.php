<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;

interface SchemaReplacerInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $schema
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface|null
   *   A transformed schema, or NULL if no replacement can be found.
   */
  public function schemaGetReplacement(CfSchemaInterface $schema): ?CfSchemaInterface;

  /**
   * @param string $schemaClass
   *
   * @return bool
   */
  public function acceptsSchemaClass(string $schemaClass): bool;

}
