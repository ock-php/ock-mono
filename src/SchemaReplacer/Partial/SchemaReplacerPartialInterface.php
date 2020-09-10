<?php
declare(strict_types=1);

namespace Donquixote\Cf\SchemaReplacer\Partial;

use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface;

interface SchemaReplacerPartialInterface {

  /**
   * @return string
   */
  public function getSourceSchemaClass(): string;

  /**
   * @param \Donquixote\Cf\Core\Schema\CfSchemaInterface $schema
   * @param \Donquixote\Cf\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\Cf\Core\Schema\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer): ?CfSchemaInterface;

}
