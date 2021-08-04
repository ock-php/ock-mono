<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Defmap\DefinitionToLabel\DefinitionToLabelInterface;
use Donquixote\OCUI\Text\Text;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\Translator\Lookup\TranslatorLookup_Passthru;
use Donquixote\OCUI\Translator\Translator;

abstract class Formula_Select_BufferedBase implements Formula_SelectInterface {

  /**
   * Buffered optgroups.
   *
   * @var \Donquixote\OCUI\Text\TextInterface[]
   */
  private $groups = [];

  /**
   * Buffered grouped options, with '' for top-level options.
   *
   * @var \Donquixote\OCUI\Text\TextInterface[][]|null
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
   * @param \Donquixote\OCUI\Text\TextInterface[][] $grouped_options
   *   Format: $[$group_id][$id] = $label,
   *   with $group_id === '' for top-level options.
   * @param \Donquixote\OCUI\Text\TextInterface[] $group_labels
   *   Format: $[$optgroup_id] = $optgroup_label.
   */
  abstract protected function initialize(array &$grouped_options, array &$group_labels): void;

}
