<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\DefinitionToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Schema\CfSchemaInterface;
use Donquixote\OCUI\Schema\Definition\CfSchema_Definition;

class DefinitionToSchema_Simple implements DefinitionToSchemaInterface {

  /**
   * {@inheritdoc}
   */
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL): CfSchemaInterface {
    return new CfSchema_Definition($definition, $context);
  }
}
