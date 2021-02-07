<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Text\TextInterface;

class Formula_Select_FromDefinitions implements Formula_SelectInterface {

  /**
   * @var array[]
   */
  private $definitions;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToLabel;

  /**
   * @var \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface
   */
  private $definitionToGroupLabel;

  /**
   * @param array[] $definitions
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToLabel
   * @param \Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface $definitionToGroupLabel
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
  public function idGetLabel($id): ?TextInterface {

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
