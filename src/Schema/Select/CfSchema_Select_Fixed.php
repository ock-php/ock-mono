<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Schema\Select;

use Donquixote\OCUI\Text\TextInterface;

class CfSchema_Select_Fixed implements CfSchema_SelectInterface {

  /**
   * @var string[][]
   */
  private $groupedOptions;

  /**
   * @var string[]
   */
  private $options;

  /**
   * @param string[] $options
   *
   * @return self
   */
  public static function createFlat(array $options): CfSchema_Select_Fixed {
    return new self(['' => $options]);
  }

  /**
   * @param string[][] $groupedOptions
   *   Format: $[$groupLabel][$optionKey] = $optionLabel,
   *   with $groupLabel === '' for toplevel options.
   */
  public function __construct(array $groupedOptions) {

    $this->groupedOptions = $groupedOptions;

    $options = [];
    foreach ($groupedOptions as $groupLabel => $groupOptions) {
      $options += $groupOptions;
    }

    $this->options = $options;
  }

  /**
   * @param string $id
   * @param string $label
   * @param string $groupLabel
   *
   * @return static
   */
  public function withOption(string $id, string $label, $groupLabel = '') {
    $clone = clone $this;
    $clone->groupedOptions[$groupLabel][$id] = $label;
    $clone->options[$id] = $label;
    return $clone;
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->options[$id]);
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(): array {
    return $this->groupedOptions;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {

    return $this->options[$id] ?? null;
  }
}
