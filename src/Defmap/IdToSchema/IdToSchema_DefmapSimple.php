<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Defmap\IdToSchema;

use Donquixote\OCUI\Context\CfContextInterface;
use Donquixote\OCUI\Core\Formula\FormulaInterface;
use Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\OCUI\IdToSchema\IdToSchemaInterface;
use Donquixote\OCUI\Formula\Definition\CfSchema_Definition;

class IdToSchema_DefmapSimple implements IdToSchemaInterface {

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\OCUI\Context\CfContextInterface|null
   */
  private $context;

  /**
   * @param \Donquixote\OCUI\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\OCUI\Context\CfContextInterface|null $context
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
  public function idGetSchema($id): ?FormulaInterface {

    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }

    return new CfSchema_Definition($definition, $this->context);
  }
}
