<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;

interface SchemaReplacerInterface {

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
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
