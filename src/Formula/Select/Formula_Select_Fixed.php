<?php
declare(strict_types=1);

namespace Donquixote\ObCK\Formula\Select;

use Donquixote\ObCK\Text\Text;
use Donquixote\ObCK\Text\TextInterface;

class Formula_Select_Fixed implements Formula_SelectInterface {

  /**
   * Optgroups.
   *
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private $groups;

  /**
   * Grouped options.
   *
   * @var \Donquixote\ObCK\Text\TextInterface[][]
   */
  private $groupedOptions;

  /**
   * Flattened options.
   *
   * @var \Donquixote\ObCK\Text\TextInterface[]
   */
  private $flatOptions;

  /**
   * @param \Donquixote\ObCK\Text\TextInterface[] $options
   *
   * @return self
   */
  public static function createFlat(array $options): self {
    return new self(['' => $options]);
  }

  /**
   * Constructor.
   *
   * @param \Donquixote\ObCK\Text\TextInterface[][] $grouped_options
   *   Format: $[$group_id][$option_value] = $option_label,
   *   with $group_id === '' for top-level options.
   * @param \Donquixote\ObCK\Text\TextInterface[] $groups
   *   Optgroup labels, without the top-level group.
   */
  public function __construct(array $grouped_options, array $groups = []) {
    $this->groupedOptions = $grouped_options;
    $this->groups = $groups;
    $this->flatOptions = $grouped_options
      ? array_replace(...array_values($grouped_options))
      : [];
  }

  /**
   * @param string $id
   * @param \Donquixote\ObCK\Text\TextInterface $label
   * @param string $group_id
   * @param \Donquixote\ObCK\Text\TextInterface|null $group_label
   *
   * @return static
   */
  public function withOption(string $id, TextInterface $label, string $group_id = '', TextInterface $group_label = NULL): self {
    $clone = clone $this;
    $clone->groupedOptions[$group_id][$id] = $label;
    $clone->flatOptions[$id] = $label;
    if ($group_id !== '') {
      $clone->groups[$group_id] = $group_label ?? Text::s($group_id);
    }
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->flatOptions[$id]);
  }

  /**
   * {@inheritdoc}
   */
  public function getOptGroups(): array {
    return $this->groups;
  }

  /**
   * {@inheritdoc}
   */
  public function getOptions(?string $group_id): array {
    return $this->groupedOptions[$group_id ?? ''] ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    return $this->flatOptions[$id] ?? null;
  }
}
