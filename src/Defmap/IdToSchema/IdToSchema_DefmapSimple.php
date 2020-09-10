<?php
declare(strict_types=1);

namespace Donquixote\Cf\Defmap\IdToSchema;

use Donquixote\Cf\Context\CfContextInterface;
use Donquixote\Cf\Core\Schema\CfSchemaInterface;
use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\IdToSchema\IdToSchemaInterface;
use Donquixote\Cf\Schema\Definition\CfSchema_Definition;

class IdToSchema_DefmapSimple implements IdToSchemaInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\Cf\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Context\CfContextInterface|null $context
   */
  public function __construct(
    DefinitionMapInterface $definitionMap,
    CfContextInterface $context = NULL
  ) {
    $this->definitionMap = $definitionMap;
    $this->context = $context;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetSchema($id): ?CfSchemaInterface {

    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }

    return new CfSchema_Definition($definition, $this->context);
  }
}
