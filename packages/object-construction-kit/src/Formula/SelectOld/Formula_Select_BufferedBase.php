<?php

declare(strict_types=1);

namespace Ock\Ock\Formula\SelectOld;

use Ock\Ock\Text\Text;

/**
 * @phpstan-ignore class.implementsDeprecatedInterface
 */
abstract class Formula_Select_BufferedBase implements Formula_SelectInterface {

  /**
   * Buffered optgroups.
   *
   * @var \Ock\Ock\Text\TextInterface[]
   */
  private array $groups = [];

  /**
   * Buffered grouped options, with '' for top-level options.
   *
   * @var \Ock\Ock\Text\TextInterface[][]|null
   */
  private ?array $groupedOptions;

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
    return $this->groupedOptions[$group_id ?? ''] ?? [];
  }

  /**
   * Initializes buffered options and groups.
   *
   * @throws \Ock\Ock\Exception\FormulaException
   *   Failure to initialize the formula.
   */
  private function init(): void {
    if ($this->groupedOptions !== NULL) {
      return;
    }
    // Use local variables, instead of writing directly to object properties.
    // This is easier to follow for static analysis (PhpStan).
    $groups = [];
    $grouped_options = ['' => []];
    $this->initialize($grouped_options, $groups);
    $grouped_options = array_filter($grouped_options);
    Text::validateNested($grouped_options);
    Text::validateMultiple($groups);
    $this->groups = $groups;
    $this->groupedOptions = $grouped_options;
  }

  /**
   * Initializes grouped options and optgroup labels.
   *
   * @param \Ock\Ock\Text\TextInterface[][] $grouped_options
   *   Format: $[$group_id][$id] = $label,
   *   with $group_id === '' for top-level options.
   * @param \Ock\Ock\Text\TextInterface[] $group_labels
   *   Format: $[$optgroup_id] = $optgroup_label.
   *
   * @throws \Ock\Ock\Exception\FormulaException
   *   Failure to initialize the formula.
   */
  abstract protected function initialize(array &$grouped_options, array &$group_labels): void;

}
