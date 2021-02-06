<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer\Partial;

use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface;

interface SchemaReplacerPartialInterface {

  /**
   * @return string
   */
  public function getSourceSchemaClass(): string;

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   * @param \Donquixote\OCUI\SchemaReplacer\SchemaReplacerInterface $replacer
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   */
  public function schemaGetReplacement(FormulaInterface $schema, SchemaReplacerInterface $replacer): ?FormulaInterface;

}
