<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\DefinitionToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Schema\Definition\CfSchema_Definition;

class DefinitionToSchema_Simple implements DefinitionToSchemaInterface {

  /**
   * {@inheritdoc}
   */
  public function definitionGetSchema(array $definition, CfContextInterface $context = NULL): CfSchemaInterface {
    return new CfSchema_Definition($definition, $context);
  }
}
