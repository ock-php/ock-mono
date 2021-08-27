<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select;

use Donquixote\ObCK\Text\Text;

abstract class Formula_Select_BufferedBase implements Formula_SelectInterface {

  /**
   * Buffered optgroups.
   *
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private $groups = [];

  /**
   * Buffered grouped options, with '' for top-level options.
   *
   * @var \Donquixote\ObCK\Text\TextInterface[][]|null
   */
  private $groupedOptions;

  /**
   * {@inheritdoc}
   */
  public function getOptGroups(): array {
    $this->init();
    return $this->groups;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(?string $group_id): array {
    $this->init();
    return $this->groupedOptions[$group_id] ?? [];
  }

  /**
   * Initializes buffered options and groups.
   */
  private function init(): void {
    if ($this->groupedOptions !== NULL) {
      return;
    }
    $this->groupedOptions = ['' => []];
    $this->initialize($this->groupedOptions, $this->groups);
    $this->groupedOptions = array_filter($this->groupedOptions);
    Text::validateNested($this->groupedOptions);
    Text::validateMultiple($this->groups);
  }

  /**
   * Initializes grouped options and optgroup labels.
   *
   * @param \Donquixote\ObCK\Text\TextInterface[][] $grouped_options
   *   Format: $[$group_id][$id] = $label,
   *   with $group_id === '' for top-level options.
   * @param \Donquixote\ObCK\Text\TextInterface[] $group_labels
   *   Format: $[$optgroup_id] = $optgroup_label.
   */
  abstract protected function initialize(array &$grouped_options, array &$group_labels): void;

}
