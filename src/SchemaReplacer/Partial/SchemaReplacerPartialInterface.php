<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\CfSchemaInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

interface SchemaReplacerPartialInterface {

  /**
   * @return string
   */
  public function getSourceSchemaClass(): string;

  /**
   * @param \Donquixote\OCUI\Core\Formula\CfSchemaInterface $schema
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\CfSchemaInterface|null
   */
  public function schemaGetReplacement(CfSchemaInterface $schema, SchemaReplacerInterface $replacer): ?CfSchemaInterface;

}
