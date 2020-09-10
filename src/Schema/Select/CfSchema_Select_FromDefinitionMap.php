<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select;

use Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface;
use Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface;

class CfSchema_Select_FromDefinitionMap implements CfSchema_SelectInterface {

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface
   */
  private $definitionMap;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGroupLabel;

  /**
   * @param \Donquixote\Cf\Defmap\DefinitionMap\DefinitionMapInterface $definitionMap
   * @param \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   */
  public function __construct(
    DefinitionMapInterface $definitionMap,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel
  ) {
    $this->definitionMap = $definitionMap;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGroupLabel = $definitionToGroupLabel;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {

    $options = ['' => []];
    foreach ($this->definitionMap->getDefinitionsById() as $id => $definition) {
      $label = $this->definitionToLabel->definitionGetLabel($definition, $id);
      $groupLabel = $this->definitionToGroupLabel->definitionGetLabel($definition, '');
      $options[$groupLabel][$id] = $label;
    }

    foreach ($options as $groupLabel => $groupOptions) {
      asort($options[$groupLabel]);
    }

    ksort($options);

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id) {
    if (NULL === $definition = $this->definitionMap->idGetDefinition($id)) {
      return NULL;
    }
    return $this->definitionToLabel->definitionGetLabel($definition, $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return NULL !== $this->definitionMap->idGetDefinition($id);
  }
}
