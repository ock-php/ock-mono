<?php
declare(strict_types=1);

namespace Donquixote\OCUI\Formula\Select;

use Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface;
use Donquixote\OCUI\Text\TextInterface;
use Donquixote\OCUI\TextToMarkup\TextToMarkupInterface;

class Formula_Select_FromOptions implements Formula_SelectInterface {

  /**
   * @var \Donquixote\OCUI\Formula\Select\Option\SelectOptionInterface[]
   */
  private $options;

  /**
   * Constructor.
   *
   * @param \Donquixote\OCUI\Formula\Select\Option\SelectOptionInterface[] $options
   */
  public function __construct(array $options) {
    self::validateOptions(...array_values($options));
    $this->options = $options;
  }

  /**
   * @param \Donquixote\OCUI\Formula\Drilldown\Option\DrilldownOptionInterface ...$options
   */
  private static function validateOptions(DrilldownOptionInterface ...$options): void {}

  /**
   * {@inheritdoc}
   */
  public function getGroupedOptions(TextToMarkupInterface $textToMarkup): array {
    // Make sure the no-label group is at the top.
    $options = ['' => []];
    foreach ($this->options as $id => $option) {
      $label = $option->getLabel();
      $group_label = $option->getGroupLabel();
      $group_label_string = $group_label !== NULL
        ? $textToMarkupo->textGetMarkup($group_label)
        : '';
      $options[$group_label_string][$id] = $label;
    }
    if (!$options['']) {
      unset($options['']);
    }
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function idGetLabel($id): ?TextInterface {
    if (!isset($this->options[$id])) {
      return NULL;
    }
    return $this->options[$id]->getLabel();
  }

  /**
   * {@inheritdoc}
   */
  public function idIsKnown($id): bool {
    return isset($this->options[$id]);
  }

}
