<?php
declare(strict_types=1);

namespace Donquixote\Cf\Schema\Select;

use Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface;

class CfSchema_Select_FromDefinitions implements CfSchema_SelectInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGroupLabel;

  /**
   * @param array[] $definitions
   * @param \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\Cf\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
   */
  public function __construct(
    array $definitions,
    DefinitionToLabelInterface $definitionToLabel,
    DefinitionToLabelInterface $definitionToGroupLabel
  ) {
    $this->definitions = $definitions;
    $this->definitionToLabel = $definitionToLabel;
    $this->definitionToGroupLabel = $definitionToGroupLabel;
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {

    $options = ['' => []];
    foreach ($this->definitions as $id => $definition) {
      $label = $this->definitionToLabel->definitionGetLabel($definition, $id);
      $groupLabel = $this->definitionToGroupLabel->definitionGetLabel($definition, '');
      $options[$groupLabel][$id] = $label;
    }

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?\Donquixote\Cf\Text\TextInterface {

    if (!isset($this->definitions[$id])) {
      return NULL;
    }

    return $this->definitionToLabel->definitionGetLabel($this->definitions[$id], $id);
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->definitions[$id]);
  }
}
