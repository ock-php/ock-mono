<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\TextToMarkup\TextToMarkupInterface;

class Formula_Select_Fixed implements Formula_SelectInterface {

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
  public static function createFlat(array $options): Formula_Select_Fixed {
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
  public function getGroupedOptions(TextToMarkupInterface $textToMarkup): array {
    return $this->groupedOptions;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {

    return $this->options[$id] ?? null;
  }
}
