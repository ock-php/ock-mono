<?php
declare(strict_types=1);

namespace Donquixote\OCUI\SchemaReplacer;

use Donquixote\OCUI\Core\Formula\FormulaInterface;

interface SchemaReplacerInterface {

  /**
   * @param \Donquixote\OCUI\Core\Formula\FormulaInterface $schema
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface|null
   *   A transformed schema, or NULL if no replacement can be found.
   */
  public function schemaGetReplacement(FormulaInterface $schema): ?FormulaInterface;

  /**
   * @param string $schemaClass
   *
   * @return bool
   */
  public function acceptsSchemaClass(string $schemaClass): bool;

}
