<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\TypeToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Iface\CfSchema_IfaceWithContext;

/**
 * This is a version of TypeToSchema* where $type is assumed to be an interface
 * name.
 */
class TypeToSchema_Iface implements TypeToSchemaInterface {

  /**
   * {@inheritdoc}
   */
  public function typeGetSchema(string $type, CfContextInterface $context = NULL): CfSchemaInterface {
    return new CfSchema_IfaceWithContext($type, $context);
  }
}
