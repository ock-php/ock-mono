<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\TypeToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Formula\Iface\CfSchema_IfaceWithContext;

/**
 * This is a version of TypeToSchema* where $type is assumed to be an interface
 * name.
 */
class TypeToSchema_Iface implements TypeToSchemaInterface {

  /**
   * {@inheritdoc}
   */
  public function typeGetSchema(string $type, CfContextInterface $context = NULL): FormulaInterface {
    return new CfSchema_IfaceWithContext($type, $context);
  }
}
