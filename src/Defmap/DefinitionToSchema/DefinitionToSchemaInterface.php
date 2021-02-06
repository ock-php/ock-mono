<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;

/**
 * Objects to create schema based on definitions.
 *
 * Definitions arrays are the format in which components register their plugins.
 */
interface DefinitionToSchemaInterface {

  /**
   * Gets or creates a schema object from a given definition array.
   *
   * @param array $definition
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
   *
   * @return \Donquixote\OCUI\Core\Formula\FormulaInterface
   *
   * @throws \Donquixote\OCUI\Exception\CfSchemaCreationException
   */
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL): FormulaInterface;

}
